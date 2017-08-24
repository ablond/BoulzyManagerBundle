<?php

/*
 * This file is part of the BoulzyManagerBundle package.
 *
 * (c) Rémi Houdelette <https://github.com/B0ulzy>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Boulzy\ManagerBundle\Factory;

use Boulzy\ManagerBundle\Exception\UnresolvedManagerException;
use Boulzy\ManagerBundle\Exception\UnsupportedClassException;
use Boulzy\ManagerBundle\Manager\ManagerInterface;
use Boulzy\ManagerBundle\Util\ManagerUtil;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Util\ClassUtils;

/**
 * Factory for managers.
 * 
 * @author Rémi Houdelette <b0ulzy.todo@gmail.com>
 */
final class ManagerFactory implements ManagerFactoryInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @var array
     */
    private $managers;

    /**
     * @var ManagerInterface
     */
    private $defaultManager;

    public function __construct(ObjectManager $om, ManagerInterface $defaultManager)
    {
        $this->om = $om;
        $this->managers = array();

        if (!ManagerUtil::isDefaultManager($defaultManager)) {
            throw new \LogicException(
                'The default manager must use the trait "Boulzy\ManagerBundle\Manager\DefaultManagerTrait".'
            );
        }
        $this->defaultManager = $defaultManager;
    }

    /**
     * {@inheritDoc}
     * 
     * @throws UnsupportedClassException The class is not supported by any manager.
     */
    public function getManager($class): ManagerInterface
    {
        $classname = $this->getClassname($class);

        // Check if a manager is registered for the class
        $manager = $this->getManagerByClass($classname);
        if ($manager !== null) {
            return $manager;
        }

        // If not, try to find a manager that supports the class
        $manager = $this->getManagerBySupportedClass($classname);
        if ($manager !== null) {
            return $manager;
        }

        if (!$this->isDoctrineModel($classname)) {
            throw new UnsupportedClassException($classname, ManagerInterface::class);
        }

        return $this->getDefaultManager($classname);
    }

    /**
     * {@inheritDoc}
     * 
     * @return $this
     */
    public function addManager(ManagerInterface $manager): ManagerFactory
    {
        $this->managers[$manager->getClass()] = $manager;

        return $this;
    }

    /**
     * Gets the real class of the model.
     * It prevents the Doctrine proxy class to prevent the factory to retrieve
     * the manager.
     * 
     * @param object|string $class
     * @return string $class
     */
    private function getClassname($class): string
    {
        return is_object($class) ? ClassUtils::getClass($class) : ClassUtils::getRealClass($class);
    }

    /**
     * Gets manager by class name.
     * 
     * @param string $classname
     * @return ManagerInterface|null
     */
    private function getManagerByClass(string $classname)
    {
        if (!key_exists($classname, $this->managers)) {
            return null;
        }

        return $this->managers[$classname];
    }

    /**
     * Gets manager supporting parent class of the class name.
     * 
     * @param string $classname
     * @return ManagerInterface|null
     * @throws NonUniqueManagerException Multiple managers are available for the given class.
     */
    private function getManagerBySupportedClass(string $classname)
    {
        $manager = null;

        foreach ($this->managers as $potentialManager) {
            if (!$potentialManager->supports($classname)) {
                continue;
            }

            if ($manager !== null) {
                $manager = $this->getBestManagerForClass($classname, $manager, $potentialManager);
            } else {
                $manager = $potentialManager;
            }
        }

        if ($manager !== null) {
            $this->managers[$classname] = $manager;
        }

        return $manager;
    }

    /**
     * Creates a default manager for the given class.
     * 
     * @return DefaultManager|null
     */
    private function getDefaultManager(string $classname): ?ManagerInterface
    {
        $manager = clone $this->defaultManager;
        $manager->setClass($classname);

        return $manager;
    }

    /**
     * Gets the most appropriate manager for the class.
     * 
     * @param string $classname
     * @return ManagerInterface
     * @throws NonUniqueManagerException No manager can be determined as most appropriate
     * @throws UnresolvedManagerException No manager could be determined
     */
    private function getBestManagerForClass(string $classname, ManagerInterface $manager1, ManagerInterface $manager2): ManagerInterface
    {
        $manageParent1 = is_subclass_of($classname, $manager1->getClass());
        $manageParent2 = is_subclass_of($classname, $manager2->getClass());

        // The first manager supports a parent class of the tested class, whereas
        // the second manager supports it for a most specific reason. $manager2 has priority.
        if ($manageParent1 === true && $manageParent2 === false) {
            return $manager2;
        }

        // The second manager supports a parent class of the tested class, whereas
        // the first manager supports it for a most specific reason. $manager1 has priority.
        if ($manageParent1 === false && $manageParent2 === true) {
            return $manager1;
        }

        // We check the parents from the closest to the furthest. The manager which
        // manages the most closest parent has priority.
        $parents = $this->getClassParents($classname);
        foreach ($parents as $parent) {
            if ($manager1->getClass() === $parent) {
                return $manager1;
            }

            if ($manager2->getClass() === $parent) {
                return $manager2;
            }
        }

        throw new UnresolvedManagerException($classname);
    }

    /**
     * Checks if a class is managed by doctrine.
     * 
     * @param string $classname
     * @return bool
     */
    private function isDoctrineModel(string $classname)
    {
        return !$this->om->getMetadataFactory()->isTransient($classname);
    }

    /**
     * Gets a class parents ordered from the closest to the furthest.
     * 
     * @param string $classname
     * @return array
     */
    private function getClassParents(string $classname): array
    {
        $reflection = new \ReflectionClass($classname);

        $parents = array();
        while ($reflection = $reflection->getParentClass()) {
            $parents[] = $reflection->getName();
        }

        return $parents;
    }
}

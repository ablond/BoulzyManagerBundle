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

use Boulzy\ManagerBundle\Exception\NonUniqueManagerException;
use Boulzy\ManagerBundle\Exception\UnresolvedManagerException;
use Boulzy\ManagerBundle\Manager\ManagerInterface;

/**
 * Factory for managers.
 * 
 * @author Rémi Houdelette <https://github.com/B0ulzy>
 */
abstract class ManagerFactory implements ManagerFactoryInterface
{
    /**
     * @var array
     */
    protected $managers;

    public function __construct()
    {
        $this->managers = array();
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
     * {@inheritDoc}
     * 
     * @throws UnresolvedManagerException The class is not supported by any manager.
     */
    public function getManager($class): ManagerInterface
    {
        $classname = $this->getClassname($class);

        // Check if a manager is registered for the class
        $manager = $this->getManagerByClass($classname);

        // If not, try to find a manager that supports the class
        if ($manager === null) {
            $manager = $this->getManagerBySupportedClass($classname);
        }

        // If no existing manager supports the class, try to create a default one
        if ($manager === null) {
            $manager = $this->getDefaultManager($classname);
        }

        // If no default manager could be created, the class is probably not suitable
        // to be handled by a manager.
        if ($manager === null) {
            throw new UnresolvedManagerException($classname);
        }

        return $manager;
    }

    /**
     * Return the class name.
     * 
     * @param object|string $class
     * @return string
     */
    protected function getClassname($class): string
    {
        return is_object($class) ? get_class($class): $class;
    }

    /**
     * Get default manager.
     * 
     * @param string $classname
     * @return ManagerInterface|null
     */
    abstract protected function getDefaultManager(string $classname);

    /**
     * Get manager by class name.
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
     * Get manager supporting parent class of the class name.
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
     * Get the most appropriate manager for the class.
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

        // Both managers supports natively the class, not because they manage a
        // parent of the class. There's no way to determine which manager is the
        // most appropriate.
        if ($manageParent1 === false && $manageParent2 === false) {
            throw new NonUniqueManagerException($classname);
        }

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
            if ($manager1->getClass() === $parent && $manager2->getClass() === $parent) {
                throw new NonUniqueManagerException($classname);
            }

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
     * Get a class parents ordered from the closest to the furthest.
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

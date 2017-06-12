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

use Boulzy\ManagerBundle\Manager\DoctrineManager;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Util\ClassUtils;

/**
 * Manager factory using DoctrineManager as default manager.
 * 
 * @author Rémi Houdelette <https://github.com/B0ulzy>
 */
class DoctrineManagerFactory extends ManagerFactory
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        parent::__construct();

        $this->om = $om;
    }

    /**
     * {@inheritDoc}
     */
    protected function getClassname($class): string
    {
        return is_object($class) ? ClassUtils::getClass($class) : $class;
    }

    /**
     * {@inheritDoc}
     */
    protected function getDefaultManager(string $classname)
    {
        if (!$this->isDoctrineModel($classname)) {
            return null;
        }

        $manager = new DoctrineManager($this->om, $classname);

        return $manager;
    }

    /**
     * Check if a class is managed by doctrine.
     * 
     * @param string $classname
     * @return bool
     */
    private function isDoctrineModel(string $classname)
    {
        return !$this->om->getMetadataFactory()->isTransient($classname);
    }
}

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

use Boulzy\ManagerBundle\Manager\ManagerInterface;

/**
 * Interface to be implemented by manager factories.
 * 
 * @author Rémi Houdelette <https://github.com/B0ulzy>
 */
interface ManagerFactoryInterface
{
    /**
     * Get the manager for the class instance / name.
     * 
     * @param object|string $class Either the class name, or an instance of the class.
     * @return ManagerInterface
     */
    public function getManager($class): ManagerInterface;

    /**
     * Add a manager to the factory.
     * 
     * @param ManagerInterface $manager
     */
    public function addManager(ManagerInterface $manager);
}

<?php

/*
 * This file is part of the BoulzyManagerBundle package.
 *
 * (c) Rémi Houdelette <https://github.com/B0ulzy>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Boulzy\ManagerBundle\Collection;

use Boulzy\ManagerBundle\Manager\ManagerInterface;

/**
 * Interface to be implemented by manager factories.
 *
 * @author Rémi Houdelette <b0ulzy.todo@gmail.com>
 */
interface ManagerCollectionInterface
{
    /**
     * Returns the manager for a specific model.
     *
     * @param object|string $model
     *
     * @return ManagerInterface
     */
    public function get($model): ManagerInterface;

    /**
     * Adds a manager to the collection.
     *
     * @param ManagerInterface $manager
     */
    public function add(ManagerInterface $manager);

    /**
     * Does the collection contain this manager?
     *
     * @param object|string $model
     *
     * @return bool
     */
    public function exists($model): bool;

    /**
     * Removes a manager for the collection.
     *
     * @param ManagerInterface $manager
     */
    public function remove(ManagerInterface $manager);
}

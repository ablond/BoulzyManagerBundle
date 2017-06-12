<?php

/*
 * This file is part of the BoulzyManagerBundle package.
 *
 * (c) Rémi Houdelette <https://github.com/B0ulzy>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Boulzy\ManagerBundle\Manager;

/**
 * Interface to be implemented by model managers.
 * 
 * @author Rémi Houdelette <https://github.com/B0ulzy>
 */
interface ManagerInterface
{
    /**
     * Get an object by an identifier.
     * 
     * @param mixed $id
     * @return object
     */
    public function get($id);

    /**
     * Get all objects.
     * 
     * @return array
     */
    public function getAll(): array;

    /**
     * Get objects by a set of criteria.
     * 
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    public function getBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array;

    /**
     * Get an object by a set of criteria.
     * 
     * @param array $criteria
     * @return object
     */
    public function getOneBy(array $criteria);

    /**
     * Create a new object.
     * 
     * @return object
     */
    public function create();

    /**
     * Save an object.
     * 
     * @param object $object
     * @throws \InvalidArgumentException The object is not supported by the manager.
     */
    public function save($object);

    /**
     * Delete an object.
     * 
     * @param object $object
     * @throws \InvalidArgumentException The object is not supported by the manager.
     */
    public function delete($object);

    /**
     * Check if the manager supports this class.
     * 
     * @return bool
     */
    public function supports($class): bool;

    /**
     * Get managed object class.
     * 
     * @return string
     */
    public function getClass(): string;
}

<?php

/*
 * This file is part of the BoulzyManagerBundle package.
 *
 * (c) Rémi Houdelette <https://github.com/B0ulzy>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Boulzy\ManagerBundle\Storage\Adapter;

/**
 * Interface to be used by storage adapters.
 * These adapters are used to give more flexibility about the storage system you want to use with your objects and managers.
 *
 * @author Rémi Houdelette <b0ulzy.todo@gmail.com>
 */
interface StorageAdapterInterface
{
    /**
     * Returns an object by its identifier.
     *
     * @internal This method should be used to retrieve an object from a persistence layer by its identifier
     *
     * @param string $className
     * @param mixed  $identifier
     *
     * @return object|null
     */
    public function find(string $className, $identifier);

    /**
     * Returns all objects supported by this manager.
     *
     * @param string $className
     *
     * @return array
     */
    public function findAll(string $className): array;

    /**
     * Returns a collection of objects according to filters, sort parameters and pagination.
     *
     * @param string     $className
     * @param array      $criteria  The criteria to filter the objects
     * @param array|null $orderBy   The parameters to use to sort the results
     * @param int[null   $limit     The number of results to return
     * @param int|null   $offset    The index of the first element to return
     *
     * @return array
     */
    public function findBy(string $className, array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array;

    /**
     * Returns a object according to criteria.
     *
     * @param string $className
     * @param array  $criteria  The criteria to filter the object
     *
     * @return object|null
     */
    public function findOneBy(string $className, array $criteria);

    public function save($object);

    public function refresh($object);

    public function delete($object);
}

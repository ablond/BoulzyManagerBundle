<?php

namespace Boulzy\ManagerBundle\Storage\Adapter;

/**
 * Interface to be used by storage adapters.
 * These adapters are used to give more flexibility about the storage system you want to use with your models and managers.
 *
 * @author Rémi Houdelette <b0ulzy.todo@gmail.com>
 */
interface StorageAdapterInterface
{
    /**
     * Returns a model by its identifier.
     *
     * @internal this method should be used to retrieve a model from a persistence layer by its identifier
     *
     * @param string $className
     * @param mixed $identifier
     *
     * @return object|null a new instance of the managed class
     */
    public function find(string $className, $identifier);

    /**
     * Returns all models supported by this manager.
     *
     * @param string $className
     *
     * @return array
     */
    public function findAll(string $className): array;

    /**
     * Returns a collection of models according to filters, sort parameters and pagination.
     *
     * @param string $className
     * @param array $criteria The criteria to filter the models
     * @param array|null $orderBy The parameters to use to sort the results
     * @param int[null $limit The number of results to return
     * @param int|null $offset The index of the first element to return
     *
     * @return array
     */
    public function findBy(string $className, array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array;

    /**
     * Returns a model according to criteria.
     *
     * @param string $className
     * @param array $criteria The criteria to filter the model
     *
     * @return object|null
     */
    public function findOneBy(string $className, array $criteria);

    public function save($object);

    public function refresh($object);

    public function delete($object);
}

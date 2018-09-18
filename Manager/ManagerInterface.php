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
 * Interface to be implemented by object managers.
 *
 * An object manager is responsible for the domain logic that applies to the object.
 * Therefore, you should have one manager by object class.
 *
 * This interface provides basic methods to handle your objects. You should add methods fitting to the actions performed
 * on your objects in your implementation of this interface.
 *
 * @author Rémi Houdelette <b0ulzy.todo@gmail.com>
 */
interface ManagerInterface
{
    /**
     * Returns an object by its identifier.
     *
     * @internal This method should be used to retrieve a model from a persistence layer by its identifier
     *
     * @param mixed $identifier
     *
     * @return object|null
     */
    public function find($identifier);

    /**
     * Returns all objects supported by this manager.
     *
     * @return array
     */
    public function findAll(): array;

    /**
     * Returns a collection of objects according to filters, sort parameters and pagination.
     *
     * @param array      $criteria The criteria to filter the objects
     * @param array|null $orderBy  The parameters to use to sort the results
     * @param int[null   $limit    The number of results to return
     * @param int|null   $offset   The index of the first element to return
     *
     * @return array
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array;

    /**
     * Returns an object according to filters.
     *
     * @param array $criteria The criteria to filter the model
     *
     * @return object|null
     */
    public function findOneBy(array $criteria);

    /**
     * Persists a new object.
     *
     * @internal This method should be used to persist in some way the object and attribute him an identifier
     *
     * @param object $object An instance of the managed class
     *
     * @return object The managed object after its creation
     *
     * @throws \Boulzy\ManagerBundle\Exception\UnsupportedClassException
     */
    public function create($object);

    /**
     * Updates an object.
     *
     * @internal This method should be used to update an object using the persistence layer
     *
     * @param object $object An instance of the managed class
     *
     * @return object The managed object after its update
     *
     * @throws \Boulzy\ManagerBundle\Exception\UnsupportedClassException
     */
    public function update($object);

    /**
     * Deletes an object.
     *
     * @internal This method should be used to delete an object from the persistence layer
     *
     * @param object $object An instance of the managed class
     *
     * @throws \Boulzy\ManagerBundle\Exception\UnsupportedClassException
     */
    public function delete($object);

    /**
     * Returns the fully qualified managed class name.
     *
     * @return string
     */
    public function getClass(): string;

    /**
     * Checks if the tested element (it can be a fully qualified class name or an object) is supported by this manager.
     * Every public method called with an unsupported parameter should throw a
     * Boulzy\ManagerBundle\Exception\UnsupportedModelException exception.
     *
     * @param object|string $testedElement Can be a fully qualified class name or an object
     *
     * @return bool True if the tested element is supported by this manager, false otherwise
     */
    public function supports($testedElement): bool;
}

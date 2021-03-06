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
 * A model manager is responsible for the domain logic that applies to the model.
 * Therefore, you should have one manager by model.
 *
 * This interface provides basic methods to handle your models. You should add methods fitting to the actions performed
 * on your models in your implementation of this interface.
 *
 * @author Rémi Houdelette <b0ulzy.todo@gmail.com>
 */
interface ManagerInterface
{
    /**
     * Creates a new model.
     *
     * @internal this method should be used to persist in some way the model and attribute him an identifier
     *
     * @param object $object an instance of the managed class
     *
     * @return object the managed object after its creation
     *
     * @throws \Boulzy\ManagerBundle\Exception\UnsupportedModelException
     */
    public function create($object);

    /**
     * Returns a model by its identifier.
     *
     * @internal this method should be used to retrieve a model from a persistence layer by its identifier
     *
     * @param mixed $identifier
     *
     * @return object a new instance of the managed class
     */
    public function get($identifier);

    /**
     * Updates a model.
     *
     * @internal this method should be used to update a model using the persistence layer
     *
     * @param object $object an instance of the managed class
     *
     * @return object the managed object after its update
     *
     * @throws \Boulzy\ManagerBundle\Exception\UnsupportedModelException
     */
    public function update($object);

    /**
     * Deletes a model.
     *
     * @internal this method should be used to delete a model from the persistence layer
     *
     * @param object $object an instance of the managed class
     *
     * @throws \Boulzy\ManagerBundle\Exception\UnsupportedModelException
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
     * @param object|string $testedElement can be a fully qualified class name or an object
     *
     * @return bool true if the tested element is supported by this manager, false otherwise
     */
    public function supports($testedElement): bool;
}

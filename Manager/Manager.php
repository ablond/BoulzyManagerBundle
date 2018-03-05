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

use Boulzy\ManagerBundle\Exception\UnsupportedModelException;
use Boulzy\ManagerBundle\Util\ClassHelper;

/**
 * Abstract class to be extended by most managers that don't rely on Doctrine.
 *
 * @author Rémi Houdelette <b0ulzy.todo@gmail.com>
 */
abstract class Manager implements ManagerInterface
{
    /**
     * {@inheritdoc}
     */
    public function create($object)
    {
        if (!$this->supports($object)) {
            throw new UnsupportedModelException(ClassHelper::getClass($object), self::class);
        }

        $this->onPreCreate($object);

        try {
            $this->save($object);
        } catch (\Exception $e) {
            $this->onCreateFailed($object, $e);
            throw $e;
        }

        $this->onPostCreate($object);

        return $object;
    }

    /**
     * {@inheritdoc}
     */
    public function update($object)
    {
        if (!$this->supports($object)) {
            throw new UnsupportedModelException(ClassHelper::getClass($object), self::class);
        }

        $this->onPreUpdate($object);

        try {
            $this->save($object);
        } catch (\Exception $e) {
            $this->onUpdateFailed($object, $e);
            throw $e;
        }

        $this->onPostUpdate($object);

        return $object;
    }

    /**
     * {@inheritdoc}
     *
     * @throws UnsupportedClassException
     */
    final public function delete($object)
    {
        if (!$this->supports($object)) {
            throw new UnsupportedModelException(ClassHelper::getClass($object), self::class);
        }

        $this->onPreDelete($object);

        try {
            $this->doDelete($object);
        } catch (\Exception $e) {
            $this->onDeleteFailed($object, $e);
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supports($object): bool
    {
        $class = ClassHelper::getClass($object);
        $supportedClass = $this->getClass();

        return $class === $supportedClass || is_subclass_of($class, $supportedClass);
    }

    /**
     * Method used to save a model in the persistence layer.
     *
     * @param $object
     */
    abstract protected function save($object);

    /**
     * Method used to delete a model in the persistence layer.
     *
     * @param $object
     */
    abstract protected function doDelete($object);

    /**
     * This method is called before a model is created.
     *
     * @param object $object
     */
    protected function onPreCreate($object)
    {
        // Implements logic for pre-create actions here
    }

    /**
     * This method is called after a model is created.
     *
     * @param object $object
     */
    protected function onPostCreate($object)
    {
        // Implements logic for post-create actions here
    }

    /**
     * This method is called when a model creation has failed.
     *
     * @param $object
     * @param \Exception|null $e
     */
    protected function onCreateFailed($object, \Exception $e = null)
    {
        // Implements logic for when creation fails here
    }

    /**
     * This method is called before a model is updated.
     *
     * @param object $object
     */
    protected function onPreUpdate($object)
    {
        // Implements logic for pre-update actions here
    }

    /**
     * This method is called after a model is updated.
     *
     * @param object $object
     */
    protected function onPostUpdate($object)
    {
        // Implements logic for post-update actions here
    }

    /**
     * This method is called after a model update has failed.
     *
     * @param $object
     * @param \Exception|null $e
     */
    protected function onUpdateFailed($object, \Exception $e = null)
    {
        // Implements logic for when update fails here
    }

    /**
     * This method is called before a model is deleted.
     *
     * @param object $object
     */
    protected function onPreDelete($object)
    {
        // Implements logic for pre-delete actions here
    }

    /**
     * This method is called after a model deletion has failed.
     *
     * @param $object
     * @param \Exception|null $e
     */
    protected function onDeleteFailed($object, \Exception $e = null)
    {
        // Implements logic for when delete fails here
    }
}

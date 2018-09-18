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

use Boulzy\ManagerBundle\Exception\UnsupportedClassException;
use Boulzy\ManagerBundle\Storage\Adapter\StorageAdapterInterface;
use Boulzy\ManagerBundle\Util\ClassHelper;

/**
 * Abstract class to be extended by most managers that don't rely on Doctrine.
 *
 * @author Rémi Houdelette <b0ulzy.todo@gmail.com>
 */
abstract class Manager implements ManagerInterface
{
    /** @var StorageAdapterInterface */
    protected $storage;

    /**
     * Manager constructor.
     *
     * @param StorageAdapterInterface $storage
     */
    public function __construct(StorageAdapterInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Alias for ObjectRepository::find() method.
     *
     * {@inheritdoc}
     */
    public function find($id)
    {
        return $this->storage->find($this->getClass(), $id);
    }

    /**
     * Alias for ObjectRepository::findAll() method.
     *
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        return $this->storage->findAll($this->getClass());
    }

    /**
     * Alias for ObjectRepository::findBy() method.
     *
     * {@inheritdoc}
     */
    public function findBy(array $criteria, array $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
        return $this->storage->findBy($this->getClass(), $criteria, $orderBy, $limit, $offset);
    }

    /**
     * Alias for ObjectRepository::findOneBy() method.
     *
     * {@inheritdoc}
     */
    public function findOneBy(array $criteria)
    {
        return $this->storage->findOneBy($this->getClass(), $criteria);
    }

    /** {@inheritdoc} */
    final public function create($object)
    {
        $this->denyIfUnsupported($object);

        $this->callMethod('onPreCreate', $object);

        try {
            $this->save($object);
        } catch (\Exception $e) {
            $this->callMethod('onCreateFailed', $object);

            throw $e;
        }

        $this->callMethod('onPostCreate', $object);

        return $object;
    }

    /** {@inheritdoc} */
    final public function update($object)
    {
        $this->denyIfUnsupported($object);

        $this->callMethod('onPreUpdate', $object);

        try {
            $this->save($object);
        } catch (\Exception $e) {
            $this->callMethod('onUpdateFailed', $object);

            throw $e;
        }

        $this->callMethod('onPostUpdate', $object);

        return $object;
    }

    /**
     * {@inheritdoc}
     *
     * @throws UnsupportedClassException
     */
    final public function delete($object)
    {
        $this->denyIfUnsupported($object);

        $this->callMethod('onPreDelete', $object);

        try {
            $this->storage->delete($object);
        } catch (\Exception $e) {
            $this->callMethod('onDeleteFailed', $object);

            throw $e;
        }
    }

    /** {@inheritdoc} */
    public function supports($object): bool
    {
        $class = ClassHelper::getClass($object);
        $supportedClass = $this->getClass();

        return $class === $supportedClass || \is_subclass_of($class, $supportedClass);
    }

    /**
     * Method used to save an object in the persistence layer.
     *
     * @param $object
     */
    final protected function save($object)
    {
        $this->storage->save($object);
    }

    /**
     * Throws an exception if the object is not supported by the manager.
     *
     * @param $object
     *
     * @throws UnsupportedClassException
     */
    final protected function denyIfUnsupported($object)
    {
        if (!$this->supports($object)) {
            throw new UnsupportedClassException(ClassHelper::getClass($object), self::class);
        }
    }

    /**
     * Calls a method if she exists.
     * This is used to add some logic on CRUD methods for example.
     *
     * @param string $method
     * @param object $object
     *
     * @return mixed
     */
    final protected function callMethod(string $method, $object)
    {
        if (\is_callable(array($this, $method))) {
            return $this->$method($object);
        }

        return null;
    }
}

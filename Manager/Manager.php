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
use Boulzy\ManagerBundle\Storage\Adapter\StorageAdapterInterface;
use Boulzy\ManagerBundle\Util\ClassHelper;

/**
 * Abstract class to be extended by most managers that don't rely on Doctrine.
 *
 * @author Rémi Houdelette <b0ulzy.todo@gmail.com>
 */
abstract class Manager implements ManagerInterface
{
    const ON_PRE_CREATE = 'onPreCreate';
    const ON_POST_CREATE = 'onPostCreate';
    const ON_CREATE_FAILED = 'onCreateFailed';

    const ON_PRE_UPDATE = 'onPreUpdate';
    const ON_POST_UPDATE = 'onPostUpdate';
    const ON_UPDATE_FAILED = 'onUpdateFailed';

    const ON_PRE_DELETE = 'onPreDelete';
    const ON_DELETE_FAILED = 'onDeleteFailed';

    /** @var StorageAdapterInterface */
    protected $storage;

    /** @var null|array */
    private $subscribers;

    /** @param StorageAdapterInterface $storage */
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
        if (!$this->supports($object)) {
            throw new UnsupportedModelException(ClassHelper::getClass($object), self::class);
        }

        $this->dispatchInternalEvent(self::ON_PRE_CREATE, $object);

        try {
            $this->save($object);
        } catch (\Exception $e) {
            $this->dispatchInternalEvent(self::ON_CREATE_FAILED, $object);

            throw $e;
        }

        $this->dispatchInternalEvent(self::ON_POST_CREATE, $object);

        return $object;
    }

    /** {@inheritdoc} */
    final public function update($object)
    {
        if (!$this->supports($object)) {
            throw new UnsupportedModelException(ClassHelper::getClass($object), self::class);
        }

        $this->dispatchInternalEvent(self::ON_PRE_UPDATE, $object);

        try {
            $this->save($object);
        } catch (\Exception $e) {
            $this->dispatchInternalEvent(self::ON_UPDATE_FAILED, $object);

            throw $e;
        }

        $this->dispatchInternalEvent(self::ON_POST_UPDATE, $object);

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

        $this->dispatchInternalEvent(self::ON_PRE_DELETE, $object);

        try {
            $this->storage->delete($object);
        } catch (\Exception $e) {
            $this->dispatchInternalEvent(self::ON_DELETE_FAILED, $object);

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
     * Return the subscribed events, their methods and priorities.
     * Higher priorities are called first. Default priority is 0.
     *
     * Example:
     * return array(
     *      self::ON_PRE_CREATE => array(
     *          array('checkAvailability', 10),
     *          'bookOrder'
     *      ),
     *      self::ON_PRE_DELETE => array(
     *          'cancelOrder'
     *      )
     * );
     *
     * @return array
     */
    protected function getSubscribedEvents(): array
    {
        return array();
    }

    /**
     * Method used to save a model in the persistence layer.
     *
     * @param $object
     */
    final protected function save($object)
    {
        $this->storage->save($object);
    }

    /**
     * Calls the subscriber methods of an event.
     *
     * @param string $event
     * @param $object
     */
    final protected function dispatchInternalEvent(string $event, $object)
    {
        if ($this->subscribers === null) {
            $this->registerInternalSubscribers();
        }

        if (!isset($this->subscribers[$event])) {
            return;
        }

        $subscribers = $this->subscribers[$event];
        foreach ($subscribers as $priority => $listeners) {
            foreach ($listeners as $listener) {
                $this->$listener($object);
            }
        }
    }

    /**
     * Registers the subscribers and their priority.
     */
    private function registerInternalSubscribers(): array
    {
        $registeredSubscribers = array();

        $subscribers = $this->getSubscribedEvents();

        foreach ($subscribers as $eventName => $params) {
            if (\is_string($params)) {
                $registeredSubscribers[$eventName][0][] = $params;
            } else if (\is_string($params[0])) {
                $priority = isset($params[1]) ? $params[1] : 0;
                $registeredSubscribers[$eventName][$priority][] = $params;
            } else {
                foreach ($params as $listener) {
                    $priority = isset($listener[1]) ? $listener[1] : 0;
                    $registeredSubscribers[$eventName][$priority][] = $listener[0];
                }
            }
        }

        $subscribedEvents = array_keys($registeredSubscribers);
        foreach ($subscribedEvents as $subscribedEvent) {
            krsort($registeredSubscribers[$subscribedEvent]);
        }

        $this->subscribers = $registeredSubscribers;
    }
}

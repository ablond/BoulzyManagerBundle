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
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\Common\Util\ClassUtils;

/**
 * A basic implementation of the ManagerInterface using Doctrine as the persistence layer.
 *
 * @author Rémi Houdelette <b0ulzy.todo@gmail.com>
 */
abstract class Manager implements ManagerInterface
{
    /**
     * @var ObjectManager
     */
    protected $om;

    /**
     * @var ObjectRepository
     */
    private $repository;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Alias for ObjectRepository::find() method.
     *
     * {@inheritdoc}
     */
    final public function get($id)
    {
        return $this->getRepository()->find($id);
    }

    /**
     * Alias for ObjectRepository::findAll() method.
     *
     * {@inheritdoc}
     */
    final public function getAll(): array
    {
        return $this->getRepository()->findAll();
    }

    /**
     * Alias for ObjectRepository::findBy() method.
     *
     * {@inheritdoc}
     */
    final public function getBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
    {
        return $this->getRepository()->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * Alias for ObjectRepository::findOneBy() method.
     *
     * {@inheritdoc}
     */
    final public function getOneBy(array $criteria)
    {
        return $this->getRepository()->findOneBy($criteria);
    }

    /**
     * {@inheritdoc}
     */
    final public function create($object)
    {
        if (!$this->supports($object)) {
            throw new UnsupportedModelException(ClassUtils::getClass($object), self::class);
        }

        $this->onPreCreate($object);

        $this->om->persist($object);
        $this->om->flush();

        $this->onPostCreate($object);

        return $object;
    }

    /**
     * {@inheritdoc}
     */
    final public function update($object)
    {
        if (!$this->supports($object)) {
            throw new UnsupportedModelException(ClassUtils::getClass($object), self::class);
        }

        $this->onPreUpdate($object);

        $this->om->flush();

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
            throw new UnsupportedModelException(ClassUtils::getClass($object), self::class);
        }

        $this->onPreDelete($object);

        $this->om->remove($object);
        $this->om->flush();
    }

    /**
     * {@inheritdoc}
     */
    final public function supports($object): bool
    {
        $class = is_object($object) ? ClassUtils::getClass($object) : $object;
        $supportedClass = $this->getClass();

        return $class === $supportedClass || is_subclass_of($class, $supportedClass);
    }

    final protected function getRepository(): ObjectRepository
    {
        if (null === $this->repository) {
            $this->repository = $this->om->getRepository($this->getClass());
        }

        return $this->repository;
    }

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
     * This method is called before a model is deleted.
     *
     * @param object $object
     */
    protected function onPreDelete($object)
    {
        // Implements logic for pre-delete actions here
    }
}

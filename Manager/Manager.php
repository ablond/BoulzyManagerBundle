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
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * Manager for Doctrine entities/documents.
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
    protected $repository;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Alias for ObjectRepository::find() method.
     * 
     * {@inheritDoc}
     */
    public function get($id)
    {
        return $this->getRepository()->find($id);
    }

    /**
     * Alias for ObjectRepository::findAll() method.
     * 
     * {@inheritDoc}
     */
    public function getAll(): array
    {
        return $this->getRepository()->findAll();
    }

    /**
     * Alias for ObjectRepository::findBy() method.
     * 
     * {@inheritDoc}
     */
    public function getBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
    {
        return $this->getRepository()->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * Alias for ObjectRepository::findOneBy() method.
     * 
     * {@inheritDoc}
     */
    public function getOneBy(array $criteria)
    {
        return $this->getRepository()->findOneBy($criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function create()
    {
        $class = $this->getClass();

        return new $class();
    }

    /**
     * {@inheritDoc}
     * 
     * @throws UnsupportedClassException
     */
    public function save($object)
    {
        if (!$this->supports($object)) {
            throw new UnsupportedClassException(get_class($object), self::class);
        }

        $this->om->persist($object);
        $this->om->flush();
    }

    /**
     * {@inheritDoc}
     * 
     * @throws UnsupportedClassException
     */
    public function delete($object)
    {
        if (!$this->supports($object)) {
            throw new UnsupportedClassException(get_class($object), self::class);
        }

        $this->om->remove($object);
        $this->om->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function supports($object): bool
    {
        $class = is_object($object) ? get_class($object) : $object;
        $supportedClass = $this->getClass();

        return $class === $supportedClass || is_subclass_of($class, $supportedClass);
    }

    /**
     * Gets the class repository.
     * 
     * @return ObjectRepository
     */
    public function getRepository(): ObjectRepository
    {
        if ($this->repository === null) {
            $this->repository = $this->om->getRepository($this->getClass());
        }

        return $this->repository;
    }
}

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
 * @author Rémi Houdelette <https://github.com/B0ulzy>
 */
class DoctrineManager extends Manager
{
    /**
     * @var ObjectRepository
     */
    protected $repository;

    /**
     * @var ObjectManager
     */
    protected $om;

    public function __construct(ObjectManager $om, $class)
    {
        parent::__construct($class);

        $this->om = $om;
        $this->repository = $this->om->getRepository($this->class);
    }

    /**
     * Alias for ObjectRepository::find() method.
     * 
     * {@inheritDoc}
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Alias for ObjectRepository::findAll() method.
     * 
     * {@inheritDoc}
     */
    public function getAll(): array
    {
        return $this->repository->findAll();
    }

    /**
     * Alias for ObjectRepository::findBy() method.
     * 
     * {@inheritDoc}
     */
    public function getBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
    {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * Alias for ObjectRepository::findOneBy() method.
     * 
     * {@inheritDoc}
     */
    public function getOneBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
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
    public function getClass(): string
    {
        return $this->repository->getClassName();
    }

    /**
     * Get repository.
     * 
     * @return ObjectRepository
     */
    public function getRepository(): ObjectRepository
    {
        return $this->repository;
    }
}

<?php

namespace Boulzy\ManagerBundle\Storage\Adapter;

use Boulzy\ManagerBundle\Exception\StorageException;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * Abstract adapter for Doctrine based implementations.
 *
 * @author Rémi Houdelette <b0ulzy.todo@gmail.com>
 */
abstract class DoctrineAdapter implements StorageAdapterInterface
{
    /** @var ObjectManager */
    protected $om;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /** {@inheritdoc} */
    public function find(string $className, $id)
    {
        return $this->om->getRepository($className)->find($id);
    }

    /** {@inheritdoc} */
    public function findAll(string $className): array
    {
        return $this->om->getRepository($className)->findAll();
    }

    /** {@inheritdoc} */
    public function findBy(string $className, array $criteria, array $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
        return $this->getRepository($className)->findBy($criteria, $orderBy, $limit, $offset);
    }

    /** {@inheritdoc} */
    public function findOneBy(string $className, array $criteria)
    {
        return $this->getRepository($className)->findOneBy($criteria);
    }

    /** @inheritdoc */
    public function save($model)
    {
        try {
            if (!$this->om->contains($model)) {
                $this->om->persist($model);
            }

            $this->om->flush();
        } catch (\Exception $e) {
            throw new StorageException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /** @inheritdoc */
    public function refresh($model)
    {
        try {
            $this->om->refresh($model);
        } catch (\Exception $e) {
            throw new StorageException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /** @inheritdoc */
    public function delete($model)
    {
        try {
            $this->om->remove($model);
            $this->om->flush();
        } catch (\Exception $e) {
            throw new StorageException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Returns the class repository.
     *
     * @param string $className
     * @return ObjectRepository
     */
    public function getRepository(string $className): ObjectRepository
    {
        return $this->om->getRepository($className);
    }
}
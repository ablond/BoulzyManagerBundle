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

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * A basic implementation of the ManagerInterface using Doctrine as the persistence layer.
 *
 * @author Rémi Houdelette <b0ulzy.todo@gmail.com>
 */
abstract class DoctrineManager extends Manager
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
    public function get($id)
    {
        return $this->getRepository()->find($id);
    }

    /**
     * Alias for ObjectRepository::findAll() method.
     *
     * {@inheritdoc}
     */
    public function getAll(): array
    {
        return $this->getRepository()->findAll();
    }

    /**
     * Alias for ObjectRepository::findBy() method.
     *
     * {@inheritdoc}
     */
    public function getBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
    {
        return $this->getRepository()->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * Alias for ObjectRepository::findOneBy() method.
     *
     * {@inheritdoc}
     */
    public function getOneBy(array $criteria)
    {
        return $this->getRepository()->findOneBy($criteria);
    }

    /**
     * Returns the repository associated with the model.
     *
     * @return ObjectRepository
     */
    protected function getRepository(): ObjectRepository
    {
        if (null === $this->repository) {
            $this->repository = $this->om->getRepository($this->getClass());
        }

        return $this->repository;
    }

    /**
     * Uses Doctrine to save the model in the database.
     *
     * @param $object
     */
    protected function save($object)
    {
        if (!$this->om->contains($object)) {
            $this->om->persist($object);
        }

        $this->om->flush($object);
    }

    /**
     * Uses Doctrine to delete the model in the database.
     *
     * @param $object
     */
    protected function doDelete($object)
    {
        $this->om->remove($object);
        $this->om->flush($object);
    }
}

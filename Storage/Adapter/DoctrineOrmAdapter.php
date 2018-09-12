<?php

namespace Boulzy\ManagerBundle\Storage\Adapter;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Storage adapter for Doctrine ORM.
 *
 * @author Rémi Houdelette <b0ulzy.todo@gmail.com>
 */
class DoctrineOrmAdapter extends DoctrineAdapter
{
    /** @param EntityManagerInterface $om */
    public function __construct(EntityManagerInterface $om)
    {
        parent::__construct($om);
    }
}
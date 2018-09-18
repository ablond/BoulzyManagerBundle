<?php

/*
 * This file is part of the BoulzyManagerBundle package.
 *
 * (c) Rémi Houdelette <https://github.com/B0ulzy>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Boulzy\ManagerBundle\Storage\Adapter;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Storage adapter for Doctrine ORM.
 *
 * @author Rémi Houdelette <b0ulzy.todo@gmail.com>
 */
class DoctrineOrmAdapter extends DoctrineAdapter
{
    /**
     * DoctrineOrmAdapter constructor.
     *
     * @param EntityManagerInterface $om
     */
    public function __construct(EntityManagerInterface $om)
    {
        parent::__construct($om);
    }
}

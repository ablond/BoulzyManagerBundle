<?php

/*
 * This file is part of the BoulzyManagerBundle package.
 *
 * (c) Rémi Houdelette <https://github.com/B0ulzy>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Boulzy\ManagerBundle\Manager;

use Boulzy\ManagerBundle\Manager\DoctrineManager;
use Tests\Boulzy\ManagerBundle\Model\Dummy1;

class Dummy1Manager extends DoctrineManager
{
    public function getClass(): string
    {
        return Dummy1::class;
    }
}

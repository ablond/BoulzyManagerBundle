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

use Tests\Boulzy\ManagerBundle\Model\Dummy2;

class Dummy2Manager extends Dummy1Manager
{
    public function getClass(): string
    {
        return Dummy2::class;
    }
}

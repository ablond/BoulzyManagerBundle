<?php

/*
 * This file is part of the BoulzyManagerBundle package.
 *
 * (c) Rémi Houdelette <https://github.com/B0ulzy>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Boulzy\ManagerBundle\Tests\Factory;

use Boulzy\ManagerBundle\Factory\ManagerFactory;
use Boulzy\ManagerBundle\Tests\Model\Dummy;
use Boulzy\ManagerBundle\Tests\Manager\DummyManager;

/**
 * Dummy manager factory used to test the ManagerFactory abstract class.
 * 
 * @author Rémi Houdelette <https://github.com/B0ulzy>
 */
class DummyManagerFactory extends ManagerFactory
{
    /**
     * {@inheritDoc}
     */
    public function getDefaultManager(string $classname) {
        if ($classname !== Dummy::class && !is_subclass_of($classname, Dummy::class)) {
            return null;
        }

        return new DummyManager($classname);
    }
}

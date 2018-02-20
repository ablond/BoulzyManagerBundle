<?php

/*
 * This file is part of the BoulzyManagerBundle package.
 *
 * (c) Rémi Houdelette <https://github.com/B0ulzy>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Boulzy\ManagerBundle\Factory;

use Boulzy\ManagerBundle\Collection\ManagerCollection;
use Boulzy\ManagerBundle\Exception\ConflictException;
use Boulzy\ManagerBundle\Exception\NotManagedModelException;
use Doctrine\Common\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;
use Tests\Boulzy\ManagerBundle\Manager\Dummy1bisManager;
use Tests\Boulzy\ManagerBundle\Manager\Dummy1Manager;
use Tests\Boulzy\ManagerBundle\Manager\Dummy1terManager;
use Tests\Boulzy\ManagerBundle\Manager\Dummy2Manager;
use Tests\Boulzy\ManagerBundle\Model\Dummy1;
use Tests\Boulzy\ManagerBundle\Model\Dummy2;
use Tests\Boulzy\ManagerBundle\Model\Dummy3;
use Tests\Boulzy\ManagerBundle\Model\Dummy4;

class ManagerCollectionTest extends TestCase
{
    public function testAdd()
    {
        $managerCollection = new ManagerCollection();
        $dummy1Manager = new Dummy1Manager($this->createMock(ObjectManager::class));
        $dummy1bisManager = new Dummy1bisManager($this->createMock(ObjectManager::class));
        $dummy1terManager = new Dummy1terManager($this->createMock(ObjectManager::class));

        /*
         * Case 1: managed class does not already exist
         */

        $managerCollection->add($dummy1Manager);
        $this->assertSame($dummy1Manager, $managerCollection->get(Dummy1::class));

        /*
         * Case 2: managed class already exists
         */

        // 2.1: new manager is a subclass of the existing class
        $managerCollection->add($dummy1bisManager);
        $this->assertSame($dummy1bisManager, $managerCollection->get(Dummy1::class));

        // 2.2: existing manager is a subclass of the existing class
        $managerCollection = new ManagerCollection();
        $managerCollection->add($dummy1bisManager);
        $this->assertSame($dummy1bisManager, $managerCollection->get(Dummy1::class));
        $managerCollection->add($dummy1Manager);
        $this->assertSame($dummy1bisManager, $managerCollection->get(Dummy1::class));

        // 2.3: existing manager has no relation with the new manager
        $this->expectException(ConflictException::class);
        $managerCollection->add($dummy1terManager);
    }

    public function testGet()
    {
        $managerCollection = new ManagerCollection();
        $dummy1Manager = new Dummy1Manager($this->createMock(ObjectManager::class));
        $dummy2Manager = new Dummy2Manager($this->createMock(ObjectManager::class));

        // Case 1: managed class is already registered
        $managerCollection->add($dummy1Manager);
        $this->assertSame($dummy1Manager, $managerCollection->get(Dummy1::class));

        // Case 2: managed class is supported by only one manager
        $this->assertSame($dummy1Manager, $managerCollection->get(Dummy2::class));

        // Case 3: managed class is supported by more than one manager
        $managerCollection = new ManagerCollection();
        $managerCollection->add($dummy1Manager);
        $managerCollection->add($dummy2Manager);
        $this->expectException(ConflictException::class);
        $managerCollection->get(Dummy3::class);

        // Case 4: managed class is not supported
        $this->expectException(NotManagedModelException::class);
        $managerCollection->get(Dummy4::class);
    }

    public function testExists()
    {
        $managerCollection = new ManagerCollection();
        $dummy1Manager = new Dummy1Manager($this->createMock(ObjectManager::class));
        $dummy2Manager = new Dummy2Manager($this->createMock(ObjectManager::class));

        $this->assertFalse($managerCollection->exists(Dummy1::class));
        $managerCollection->add($dummy1Manager);
        $this->assertTrue($managerCollection->exists(Dummy1::class));

        $managerCollection = new ManagerCollection();
        $managerCollection->add($dummy1Manager);
        $managerCollection->add($dummy2Manager);
        $this->assertFalse($managerCollection->exists(Dummy3::class));
    }

    public function testRemove()
    {
        $dummy1Manager = new Dummy1Manager($this->createMock(ObjectManager::class));

        $managerCollection = new ManagerCollection();
        $managerCollection->add($dummy1Manager);

        $this->assertSame($dummy1Manager, $managerCollection->get(Dummy1::class));

        $managerCollection->remove($dummy1Manager);
        $this->expectException(NotManagedModelException::class);

        $managerCollection->get(Dummy1::class);
    }

    public function testGetModelClass()
    {
        $managerCollection = new ManagerCollection();

        $this->expectException(\InvalidArgumentException::class);
        $managerCollection->get('toto');
    }
}

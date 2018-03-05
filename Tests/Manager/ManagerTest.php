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

use Boulzy\ManagerBundle\Exception\UnsupportedModelException;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Tests\Boulzy\ManagerBundle\Model\Dummy1;
use Tests\Boulzy\ManagerBundle\Model\Dummy4;

class ManagerTest extends TestCase
{
    public function testGet()
    {
        $om = $this->createMock(ObjectManager::class);
        $repository = $this->createMock(ObjectRepository::class);
        $repository->expects($this->once())->method('find')->with(123);
        $om->expects($this->once())->method('getRepository')->with(Dummy1::class)->willReturn($repository);

        $dummy1Manager = new Dummy1Manager($om);
        $dummy1Manager->get(123);
    }

    public function testGetAll()
    {
        $om = $this->createMock(ObjectManager::class);
        $repository = $this->createMock(ObjectRepository::class);
        $repository->expects($this->once())->method('findAll')->willReturn(array());
        $om->expects($this->once())->method('getRepository')->with(Dummy1::class)->willReturn($repository);

        $dummy1Manager = new Dummy1Manager($om);
        $dummy1Manager->getAll();
    }

    public function testGetBy()
    {
        $om = $this->createMock(ObjectManager::class);
        $repository = $this->createMock(ObjectRepository::class);
        $repository->expects($this->once())->method('findBy')->with(array('attribute' => 'ok'))->willReturn(array());
        $om->expects($this->once())->method('getRepository')->with(Dummy1::class)->willReturn($repository);

        $dummy1Manager = new Dummy1Manager($om);
        $dummy1Manager->getBy(array('attribute' => 'ok'));
    }

    public function testGetOneBy()
    {
        $om = $this->createMock(ObjectManager::class);
        $repository = $this->createMock(ObjectRepository::class);
        $repository->expects($this->once())->method('findOneBy')->with(array('attribute' => 'ok'));
        $om->expects($this->once())->method('getRepository')->with(Dummy1::class)->willReturn($repository);

        $dummy1Manager = new Dummy1Manager($om);
        $dummy1Manager->getOneBy(array('attribute' => 'ok'));
    }

    public function testCreate()
    {
        $model = new Dummy1();

        $om = $this->createMock(ObjectManager::class);
        $om->expects($this->once())->method('persist')->with($model);
        $om->expects($this->once())->method('flush');

        $dummy1Manager = new Dummy1Manager($om);
        $dummy1Manager->create($model);
    }

    public function testCreateWithUnsupportedModel()
    {
        $model = new Dummy4();

        $om = $this->createMock(ObjectManager::class);

        $dummy1Manager = new Dummy1Manager($om);
        $this->expectException(UnsupportedModelException::class);
        $dummy1Manager->create($model);
    }

    public function testUpdate()
    {
        $model = new Dummy1();

        $om = $this->createMock(ObjectManager::class);
        $om->expects($this->once())->method('flush');

        $dummy1Manager = new Dummy1Manager($om);
        $dummy1Manager->update($model);
    }

    public function testUpdateWithUnsupportedModel()
    {
        $model = new Dummy4();

        $om = $this->createMock(ObjectManager::class);

        $dummy1Manager = new Dummy1Manager($om);
        $this->expectException(UnsupportedModelException::class);
        $dummy1Manager->update($model);
    }

    public function testDelete()
    {
        $model = new Dummy1();

        $om = $this->createMock(ObjectManager::class);
        $om->expects($this->once())->method('remove')->with($model);
        $om->expects($this->once())->method('flush');

        $dummy1Manager = new Dummy1Manager($om);
        $dummy1Manager->delete($model);
    }

    public function testDeleteWithUnsupportedModel()
    {
        $model = new Dummy4();

        $om = $this->createMock(ObjectManager::class);

        $dummy1Manager = new Dummy1Manager($om);
        $this->expectException(UnsupportedModelException::class);
        $dummy1Manager->delete($model);
    }

    public function testOnFailedMethods()
    {
        $model = new Dummy1();

        $om = $this->createMock(ObjectManager::class);
        $logger = $this->createMock(LoggerInterface::class);
        /*$logger
            ->expects($this->exactly(3))
            ->method('alert')
            ->with('Database is down.')
        ;*/

        $buggedManager = new BuggedManager($om, $logger);

        try {
            $buggedManager->create($model);
        } catch (\Exception $e) {
            $this->assertSame('Database is down.', $e->getMessage());
        }

        try {
            $buggedManager->update($model);
        } catch (\Exception $e) {
            $this->assertSame('Database is down.', $e->getMessage());
        }

        try {
            $buggedManager->delete($model);
        } catch (\Exception $e) {
            $this->assertSame('Database is down.', $e->getMessage());
        }
    }
}

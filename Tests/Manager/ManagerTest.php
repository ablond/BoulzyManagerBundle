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

use Boulzy\ManagerBundle\Exception\UnsupportedClassException;
use Boulzy\ManagerBundle\Storage\Adapter\StorageAdapterInterface;
use PHPUnit\Framework\TestCase;
use Tests\Boulzy\ManagerBundle\Model\Dummy;

class ManagerTest extends TestCase
{
    public function testFind()
    {
        $dummy = new Dummy();

        $storage = $this->createMock(StorageAdapterInterface::class);
        $storage
            ->expects($this->once())
            ->method('find')
            ->with(Dummy::class, 'abc123')
            ->willReturn($dummy)
        ;

        $manager = new DummyManager($storage);
        $this->assertSame($dummy, $manager->find('abc123'));
    }

    public function testFindAll()
    {
        $dummies = array(
            new Dummy(),
            new Dummy(),
        );

        $storage = $this->createMock(StorageAdapterInterface::class);
        $storage
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($dummies)
        ;

        $manager = new DummyManager($storage);
        $this->assertSame($dummies, $manager->findAll());
    }

    public function testFindBy()
    {
        $dummies = array(
            new Dummy(),
            new Dummy(),
        );

        $storage = $this->createMock(StorageAdapterInterface::class);
        $storage
            ->expects($this->once())
            ->method('findBy')
            ->with(
                Dummy::class,
                array('key1' => 'value1', 'key2' => 'value2'),
                array('key3' => 'ASC'),
                20,
                0
            )
            ->willReturn($dummies)
        ;

        $manager = new DummyManager($storage);
        $this->assertSame($dummies, $manager->findBy(
            array('key1' => 'value1', 'key2' => 'value2'),
            array('key3' => 'ASC'),
            20,
            0
        ));
    }

    public function testFindOneBy()
    {
        $dummy = new Dummy();

        $storage = $this->createMock(StorageAdapterInterface::class);
        $storage
            ->expects($this->once())
            ->method('findOneBy')
            ->with(
                Dummy::class,
                array('key1' => 'value1', 'key2' => 'value2')
            )
            ->willReturn($dummy)
        ;

        $manager = new DummyManager($storage);
        $this->assertSame($dummy, $manager->findOneBy(
            array('key1' => 'value1', 'key2' => 'value2')
        ));
    }

    public function testCreateWithInvalidArgument()
    {
        $storage = $this->createMock(StorageAdapterInterface::class);
        $manager = new DummyManager($storage);

        $this->expectException(UnsupportedClassException::class);
        $manager->create(new \stdClass());
    }

    public function testCreate()
    {
        $dummy = new Dummy();

        $storage = $this->createMock(StorageAdapterInterface::class);
        $storage
            ->expects($this->once())
            ->method('save')
            ->with($dummy)
        ;

        $manager = new DummyManager($storage);
        $this->assertSame($dummy, $manager->create($dummy));

        $this->assertTrue($dummy->onPreCreateCalled);
        $this->assertTrue($dummy->onPostCreateCalled);
    }

    public function testCreateWithException()
    {
        $dummy = new Dummy();

        $storage = $this->createMock(StorageAdapterInterface::class);
        $storage
            ->expects($this->once())
            ->method('save')
            ->with($dummy)
            ->willThrowException(new \RuntimeException())
        ;

        $this->expectException(\RuntimeException::class);
        $manager = new DummyManager($storage);
        $manager->create($dummy);

        $this->assertTrue($dummy->onPreCreateCalled);
        $this->assertTrue($dummy->onCreateFailedCalled);
        $this->assertFalse($dummy->onPostCreateCalled);
    }

    public function testUpdateWithInvalidArgument()
    {
        $storage = $this->createMock(StorageAdapterInterface::class);
        $manager = new DummyManager($storage);

        $this->expectException(UnsupportedClassException::class);
        $manager->update(new \stdClass());
    }

    public function testUpdate()
    {
        $dummy = new Dummy();

        $storage = $this->createMock(StorageAdapterInterface::class);
        $storage
            ->expects($this->once())
            ->method('save')
            ->with($dummy)
        ;

        $manager = new DummyManager($storage);
        $this->assertSame($dummy, $manager->update($dummy));

        $this->assertTrue($dummy->onPreUpdateCalled);
        $this->assertTrue($dummy->onPostUpdateCalled);
    }

    public function testUpdateWithException()
    {
        $dummy = new Dummy();

        $storage = $this->createMock(StorageAdapterInterface::class);
        $storage
            ->expects($this->once())
            ->method('save')
            ->with($dummy)
            ->willThrowException(new \RuntimeException())
        ;

        $this->expectException(\RuntimeException::class);
        $manager = new DummyManager($storage);
        $manager->update($dummy);

        $this->assertTrue($dummy->onPreUpdateCalled);
        $this->assertTrue($dummy->onUpdateFailedCalled);
        $this->assertFalse($dummy->onPostUpdateCalled);
    }

    public function testDeleteWithInvalidArgument()
    {
        $storage = $this->createMock(StorageAdapterInterface::class);
        $manager = new DummyManager($storage);

        $this->expectException(UnsupportedClassException::class);
        $manager->delete(new \stdClass());
    }

    public function testDelete()
    {
        $dummy = new Dummy();

        $storage = $this->createMock(StorageAdapterInterface::class);
        $storage
            ->expects($this->once())
            ->method('delete')
            ->with($dummy)
        ;

        $manager = new DummyManager($storage);
        $manager->delete($dummy);

        $this->assertTrue($dummy->onPreDeleteCalled);
    }

    public function testDeleteWithException()
    {
        $dummy = new Dummy();

        $storage = $this->createMock(StorageAdapterInterface::class);
        $storage
            ->expects($this->once())
            ->method('delete')
            ->with($dummy)
            ->willThrowException(new \RuntimeException())
        ;

        $this->expectException(\RuntimeException::class);
        $manager = new DummyManager($storage);
        $manager->delete($dummy);

        $this->assertTrue($dummy->onPreDeleteCalled);
        $this->assertTrue($dummy->onDeleteFailedCalled);
    }

    public function testCallMethodWithUndefinedMethod()
    {
        $dummy = new Dummy();

        $storage = $this->createMock(StorageAdapterInterface::class);
        $storage
            ->expects($this->once())
            ->method('save')
            ->with($dummy)
        ;

        $manager = new SimpleManager($storage);
        $this->assertSame($dummy, $manager->create($dummy));

        $this->assertFalse($dummy->onPreCreateCalled);
        $this->assertFalse($dummy->onPostCreateCalled);
    }
}

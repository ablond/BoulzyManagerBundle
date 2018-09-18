<?php

/*
 * This file is part of the BoulzyManagerBundle package.
 *
 * (c) Rémi Houdelette <https://github.com/B0ulzy>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Boulzy\ManagerBundle\Storage\Adapter;

use Boulzy\ManagerBundle\Exception\StorageException;
use Boulzy\ManagerBundle\Storage\Adapter\DoctrineOrmAdapter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use Tests\Boulzy\ManagerBundle\Model\Dummy;

class DoctrineAdapterTest extends TestCase
{
    public function testFind()
    {
        $dummy = new Dummy();

        $repository = $this->createMock(EntityRepository::class);
        $repository
            ->expects($this->once())
            ->method('find')
            ->with('abc123')
            ->willReturn($dummy)
        ;

        $om = $this->createMock(EntityManagerInterface::class);
        $om
            ->expects($this->once())
            ->method('getRepository')
            ->with(Dummy::class)
            ->willReturn($repository)
        ;

        $adapter = new DoctrineOrmAdapter($om);

        $this->assertSame($dummy, $adapter->find(Dummy::class, 'abc123'));
    }

    public function testFindAll()
    {
        $dummies = array(
            new Dummy(),
            new Dummy(),
        );

        $repository = $this->createMock(EntityRepository::class);
        $repository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($dummies)
        ;

        $om = $this->createMock(EntityManagerInterface::class);
        $om
            ->expects($this->once())
            ->method('getRepository')
            ->with(Dummy::class)
            ->willReturn($repository)
        ;

        $adapter = new DoctrineOrmAdapter($om);

        $this->assertSame($dummies, $adapter->findAll(Dummy::class));
    }

    public function testFindBy()
    {
        $dummies = array(
            new Dummy(),
            new Dummy(),
        );

        $repository = $this->createMock(EntityRepository::class);
        $repository
            ->expects($this->once())
            ->method('findBy')
            ->with(
                array('key1' => 'value1', 'key2' => 'value2'),
                array('key3' => 'ASC'),
                20,
                0
            )
            ->willReturn($dummies)
        ;

        $om = $this->createMock(EntityManagerInterface::class);
        $om
            ->expects($this->once())
            ->method('getRepository')
            ->with(Dummy::class)
            ->willReturn($repository)
        ;

        $adapter = new DoctrineOrmAdapter($om);

        $this->assertSame($dummies, $adapter->findBy(
            Dummy::class,
            array('key1' => 'value1', 'key2' => 'value2'),
            array('key3' => 'ASC'),
            20,
            0
        ));
    }

    public function testFindOneBy()
    {
        $dummy = new Dummy();

        $repository = $this->createMock(EntityRepository::class);
        $repository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(array('key1' => 'value1', 'key2' => 'value2'))
            ->willReturn($dummy)
        ;

        $om = $this->createMock(EntityManagerInterface::class);
        $om
            ->expects($this->once())
            ->method('getRepository')
            ->with(Dummy::class)
            ->willReturn($repository)
        ;

        $adapter = new DoctrineOrmAdapter($om);

        $this->assertSame($dummy, $adapter->findOneBy(
            Dummy::class,
            array('key1' => 'value1', 'key2' => 'value2')
        ));
    }

    public function testSaveWithNewObject()
    {
        $dummy = new Dummy();

        $om = $this->createMock(EntityManagerInterface::class);
        $om
            ->expects($this->once())
            ->method('contains')
            ->with($dummy)
            ->willReturn(false)
        ;
        $om
            ->expects($this->once())
            ->method('persist')
            ->with($dummy)
        ;
        $om
            ->expects($this->once())
            ->method('flush')
        ;

        $adapter = new DoctrineOrmAdapter($om);

        $adapter->save($dummy);
    }

    public function testSaveWithExistingObject()
    {
        $dummy = new Dummy();

        $om = $this->createMock(EntityManagerInterface::class);
        $om
            ->expects($this->once())
            ->method('contains')
            ->with($dummy)
            ->willReturn(true)
        ;
        $om
            ->expects($this->never())
            ->method('persist')
            ->with($dummy)
        ;
        $om
            ->expects($this->once())
            ->method('flush')
        ;

        $adapter = new DoctrineOrmAdapter($om);

        $adapter->save($dummy);
    }

    public function testSaveWithException()
    {
        $dummy = new Dummy();

        $om = $this->createMock(EntityManagerInterface::class);
        $om
            ->expects($this->once())
            ->method('contains')
            ->with($dummy)
            ->willReturn(true)
        ;
        $om
            ->expects($this->never())
            ->method('persist')
            ->with($dummy)
        ;
        $om
            ->expects($this->once())
            ->method('flush')
            ->willThrowException(new \RuntimeException())
        ;

        $adapter = new DoctrineOrmAdapter($om);

        $this->expectException(StorageException::class);
        $adapter->save($dummy);
    }

    public function testRefresh()
    {
        $dummy = new Dummy();

        $om = $this->createMock(EntityManagerInterface::class);
        $om
            ->expects($this->once())
            ->method('refresh')
            ->with($dummy)
        ;

        $adapter = new DoctrineOrmAdapter($om);
        $adapter->refresh($dummy);
    }

    public function testRefreshWithException()
    {
        $dummy = new Dummy();

        $om = $this->createMock(EntityManagerInterface::class);
        $om
            ->expects($this->once())
            ->method('refresh')
            ->with($dummy)
            ->willThrowException(new \RuntimeException())
        ;

        $adapter = new DoctrineOrmAdapter($om);

        $this->expectException(StorageException::class);
        $adapter->refresh($dummy);
    }

    public function testDelete()
    {
        $dummy = new Dummy();

        $om = $this->createMock(EntityManagerInterface::class);
        $om
            ->expects($this->once())
            ->method('remove')
            ->with($dummy)
        ;
        $om
            ->expects($this->once())
            ->method('flush')
        ;

        $adapter = new DoctrineOrmAdapter($om);
        $adapter->delete($dummy);
    }

    public function testDeleteWithException()
    {
        $dummy = new Dummy();

        $om = $this->createMock(EntityManagerInterface::class);
        $om
            ->expects($this->once())
            ->method('remove')
            ->with($dummy)
            ->willThrowException(new \RuntimeException())
        ;

        $adapter = new DoctrineOrmAdapter($om);

        $this->expectException(StorageException::class);
        $adapter->delete($dummy);
    }
}

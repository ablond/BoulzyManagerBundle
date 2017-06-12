<?php

/*
 * This file is part of the BoulzyManagerBundle package.
 *
 * (c) Rémi Houdelette <https://github.com/B0ulzy>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Boulzy\ManagerBundle\Tests\Manager;

use Boulzy\ManagerBundle\Manager\DoctrineManager;
use Boulzy\ManagerBundle\Tests\Entity\Dummy;
use Boulzy\ManagerBundle\Tests\Entity\UnsupportedDummy;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;

/**
 * Test class for DoctrineManager abstract class.
 * 
 * @author Rémi Houdelette <https://github.com/B0ulzy>
 */
class DoctrineManagerTest extends TestCase
{
    /**
     * Test DoctrineManager::get() method.
     */
    public function testGet()
    {
        $repository = $this->getObjectRepositoryMock();
        $repository->expects($this->once())
                   ->method('find')
                   ->with(123)
                   ->willReturn(new Dummy());

        $manager = $this->getDoctrineManager(null, $repository);
        $dummy = $manager->get(123);

        $this->assertInstanceOf(Dummy::class, $dummy);
    }

    /**
     * Test DoctrineManager::getAll() method.
     */
    public function testGetAll()
    {
        $repository = $this->getObjectRepositoryMock();
        $repository->expects($this->once())
                   ->method('findAll')
                   ->willReturn(array(
                       new Dummy(),
                       new Dummy()
                   ));

        $manager = $this->getDoctrineManager(null, $repository);
        $dummies = $manager->getAll(123);

        $this->assertTrue(is_array($dummies));
        $this->assertEquals(2, count($dummies));
        foreach ($dummies as $dummy) {
            $this->assertInstanceOf(Dummy::class, $dummy);
        }
    }

    /**
     * Test DoctrineManager::getBy() method.
     */
    public function testGetBy()
    {
        $repository = $this->getObjectRepositoryMock();
        $repository->expects($this->once())
                   ->method('findBy')
                   ->with(array('test' => 123))
                   ->willReturn(array(
                       new Dummy(),
                       new Dummy()
                    ));

        $manager = $this->getDoctrineManager(null, $repository);
        $dummies = $manager->getBy(array('test' => 123));

        $this->assertTrue(is_array($dummies));
        $this->assertEquals(2, count($dummies));
        foreach ($dummies as $dummy) {
            $this->assertInstanceOf(Dummy::class, $dummy);
        }
    }

    /**
     * Test DoctrineManager::getOneBy() method.
     */
    public function testGetOneBy()
    {
        $repository = $this->getObjectRepositoryMock();
        $repository->expects($this->once())
                   ->method('findOneBy')
                   ->with(array('test' => 123))
                   ->willReturn(new Dummy());

        $manager = $this->getDoctrineManager(null, $repository);
        $dummy = $manager->getOneBy(array('test' => 123));

        $this->assertInstanceOf(Dummy::class, $dummy);
    }

    /**
     * Test DoctrineManager::save() method with a supported object.
     */
    public function testSaveWithSupportedObject()
    {
        $dummy = new Dummy();

        $om = $this->getObjectManagerMock();
        $om->expects($this->once())
           ->method('persist')
           ->with($dummy)
           ->willReturnSelf();

        $om->expects($this->once())
           ->method('flush');

        $manager = $this->getDoctrineManager($om);
        $manager->save($dummy);
    }

    /**
     * Test DoctrineManager::save() method with an unsupported object.
     * 
     * @expectedException \Boulzy\ManagerBundle\Exception\UnsupportedClassException
     */
    public function testSaveWithUnsupportedObject()
    {
        $dummy = new UnsupportedDummy();

        $this->getDoctrineManager()->save($dummy);
    }

    /**
     * Test DoctrineManager::delete() method with a supported object.
     */
    public function testDeleteWithSupportedObject()
    {
        $dummy = new Dummy();

        $om = $this->getObjectManagerMock();
        $om->expects($this->once())
           ->method('remove')
           ->with($dummy)
           ->willReturnSelf();

        $om->expects($this->once())
           ->method('flush');

        $manager = $this->getDoctrineManager($om);
        $manager->delete($dummy);
    }

    /**
     * Test DoctrineManager::delete() method with an unsupported object.
     * 
     * @expectedException \Boulzy\ManagerBundle\Exception\UnsupportedClassException
     */
    public function testDeleteWithUnsupportedObject()
    {
        $dummy = new UnsupportedDummy();

        $this->getDoctrineManager()->delete($dummy);
    }

    /**
     * Test DoctrineManager::getClass() method.
     */
    public function testGetClass()
    {
        $manager = $this->getDoctrineManager();
        $class = $manager->getClass();

        $this->assertEquals(Dummy::class, $class);
    }

    /**
     * Test DoctrineManager::getRepository() method.
     */
    public function testGetRepository()
    {
        $manager = $this->getDoctrineManager();

        $repository = $manager->getRepository();

        $this->assertInstanceOf(ObjectRepository::class, $repository);
        $this->assertEquals(Dummy::class, $repository->getClassName());
    }

    /**
     * Get Doctrine manager.
     * 
     * @param ObjectManager $om
     * @param ObjectRepository $repository
     * @return DoctrineManager
     */
    private function getDoctrineManager(ObjectManager $om = null, ObjectRepository $repository = null)
    {
        if ($repository === null) {
            $repository = $this->getObjectRepositoryMock();
        }

        if ($om === null) {
            $om = $this->getObjectManagerMock($repository);
        }

        return new DoctrineManager($om, Dummy::class);
    }

    /**
     * Get ObjectManager mock.
     * 
     * @return ObjectManager
     */
    private function getObjectManagerMock(ObjectRepository $repository = null): ObjectManager
    {
        if ($repository === null) {
            $repository = $this->getObjectRepositoryMock();
        }

        $om = $this->createMock(ObjectManager::class);

        $om->method('getRepository')
           ->willReturn($repository);

        return $om;
    }

    /**
     * Get ObjectRepository mock.
     * 
     * @return ObjectRepository
     */
    private function getObjectRepositoryMock(): ObjectRepository
    {
        $repository = $this->createMock(ObjectRepository::class);

        $repository->method('getClassName')
                   ->willReturn(Dummy::class);

        return $repository;
    }
}

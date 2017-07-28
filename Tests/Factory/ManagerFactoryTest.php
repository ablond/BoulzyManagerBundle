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

use Boulzy\ManagerBundle\Tests\Entity\Dummy;
use Boulzy\ManagerBundle\Tests\Entity\SubDummy;
use Boulzy\ManagerBundle\Tests\Entity\SubSubDummy;
use Boulzy\ManagerBundle\Tests\Model\UnsupportedDummy;
use Boulzy\ManagerBundle\Factory\ManagerFactory;
use Boulzy\ManagerBundle\Manager\DefaultManager;
use Boulzy\ManagerBundle\Manager\ManagerInterface;
use Doctrine\Common\Persistence\Mapping\ClassMetadataFactory;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;

/**
 * Test class for ManagerFactory abstract class.
 * 
 * @author Rémi Houdelette <b0ulzy.todo@gmail.com>
 */
class ManagerFactoryTest extends TestCase
{
    /**
     * @var ManagerFactory
     */
    private $managerFactory;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $om = $this->createMock(ObjectManager::class);

        $metadataFactory = $this->createMock(ClassMetadataFactory::class);
        $metadataFactory->method('isTransient')
           ->will($this->returnCallback(function() {
               $classname = func_get_arg(0);

               return strpos($classname, '\\Entity\\') === false;
           }));

        $om->method('getMetaDataFactory')
                ->willReturn($metadataFactory);
        
        $defaultManager = new DefaultManager($om);

        $this->managerFactory = new ManagerFactory($om, $defaultManager);
    }

    /**
     * Tests ManagerFactory::addManager() method.
     */
    public function testAddManager()
    {
        $manager = $this->getManagerMock();
        $this->managerFactory->addManager($manager);

        $manager = $this->managerFactory->getManager(Dummy::class);
        $this->assertInstanceOf(ManagerInterface::class, $manager);
    }

    /**
     * Tests ManagerFactory::getManager() method with no manager registered.
     */
    public function testGetDefaultManager()
    {
        $manager = $this->managerFactory->getManager(Dummy::class);
        $this->assertInstanceOf(ManagerInterface::class, $manager);
    }

    /**
     * Tests ManagerFactory::getManager() method with a supported class.
     */
    public function testGetManagerWithSupportedClass()
    {
        $manager = $this->getManagerMock();
        $this->managerFactory->addManager($manager);

        $manager = $this->managerFactory->getManager(Dummy::class);
        $this->assertInstanceOf(ManagerInterface::class, $manager);
    }

    /**
     * Tests ManagerFactory::getManager() method with a supported object.
     */
    public function testGetManagerWithSupportedObject()
    {
        $manager = $this->getManagerMock();
        $this->managerFactory->addManager($manager);

        $dummy = new Dummy();
        $manager = $this->managerFactory->getManager($dummy);
        $this->assertInstanceOf(ManagerInterface::class, $manager);
    }

    /**
     * Tests ManagerFactory::getManager() method with a supported class.
     */
    public function testGetManagerWithSupportedExtendedClass()
    {
        $manager = $this->getManagerMock();
        $this->managerFactory->addManager($manager);

        $manager = $this->managerFactory->getManager(SubDummy::class);
        $this->assertInstanceOf(ManagerInterface::class, $manager);
    }

    /**
     * Tests ManagerFactory::getManager() method with a supported object.
     */
    public function testGetManagerWithSupportedExtendedObject()
    {
        $manager = $this->getManagerMock();
        $this->managerFactory->addManager($manager);

        $dummy = new SubDummy();
        $manager = $this->managerFactory->getManager($dummy);
        $this->assertInstanceOf(ManagerInterface::class, $manager);
    }

    /**
     * Tests ManagerFactory::getManager() method with an unsupported class.
     * 
     * @expectedException \Boulzy\ManagerBundle\Exception\UnsupportedClassException
     */
    public function testGetManagerWithUnsupportedClass()
    {
        $manager = $this->getManagerMock();
        $this->managerFactory->addManager($manager);

        $manager = $this->managerFactory->getManager(UnsupportedDummy::class);
    }

    /**
     * Tests ManagerFactory::getManager() method with an unsupported object.
     * 
     * @expectedException \Boulzy\ManagerBundle\Exception\UnsupportedClassException
     */
    public function testGetManagerWithUnsupportedObject()
    {
        $manager = $this->getManagerMock();
        $this->managerFactory->addManager($manager);

        $dummy = new UnsupportedDummy();
        $manager = $this->managerFactory->getManager($dummy);
    }

    /**
     * Tests ManagerFactory::getManager() method with multiple supporting managers.
     */
    public function testGetManagerWithMultipleManagers()
    {
        $dummyManager = $this->createManager(Dummy::class);
        $extendedDummyManager = $this->createManager(SubDummy::class);
        $unsupportedDummyManager = $this->createManager(\Boulzy\ManagerBundle\Tests\Entity\UnsupportedDummy::class);

        $this->managerFactory
            ->addManager($dummyManager)
            ->addManager($extendedDummyManager)
            ->addManager($unsupportedDummyManager)
        ;

        $manager = $this->managerFactory->getManager(SubSubDummy::class);

        $this->assertInstanceOf(ManagerInterface::class, $manager);
    }

    /**
     * Gets a manager mock for a class.
     * 
     * @param string|null $class
     * @return ManagerInterface
     */
    private function getManagerMock(string $class = null)
    {
        $class = $class ?? Dummy::class;

        $manager = $this->createMock(ManagerInterface::class);
        $manager->method('getClass')
                ->willReturn($class);

        return $manager;
    }

    /**
     * Returns an instance of the DefaultManager for a class.
     * 
     * @param string $class
     * @return DefaultManager
     */
    public function createManager(string $class)
    {
        $manager = new DefaultManager($this->getObjectManagerMock());
        $manager->setClass($class);

        return $manager;
    }

    /**
     * Gets ObjectManager mock.
     * 
     * @return ObjectRepository|null
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
     * Gets ObjectRepository mock.
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

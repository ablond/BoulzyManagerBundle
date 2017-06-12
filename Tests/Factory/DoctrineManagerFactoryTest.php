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
use Boulzy\ManagerBundle\Tests\Entity\ExtendedDummy;
use Boulzy\ManagerBundle\Tests\Model\UnsupportedDummy;
use Boulzy\ManagerBundle\Factory\DoctrineManagerFactory;
use Boulzy\ManagerBundle\Manager\ManagerInterface;
use Doctrine\Common\Persistence\Mapping\ClassMetadataFactory;
use Doctrine\Common\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;

/**
 * Test class for DoctrineManagerFactory abstract class.
 * 
 * @author Rémi Houdelette <https://github.com/B0ulzy>
 */
class DoctrineManagerFactoryTest extends TestCase
{
    /**
     * @var DoctrineManagerFactory
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
        

        $this->managerFactory = new DoctrineManagerFactory($om);
    }

    /**
     * Test ManagerFactory::addManager() method.
     */
    public function testAddManager()
    {
        $manager = $this->getManagerMock();
        $this->managerFactory->addManager($manager);

        $manager = $this->managerFactory->getManager(Dummy::class);
        $this->assertInstanceOf(ManagerInterface::class, $manager);
    }

    /**
     * Test ManagerFactory::getManager() method with no manager registered.
     */
    public function testGetDefaultManager()
    {
        $manager = $this->managerFactory->getManager(Dummy::class);
        $this->assertInstanceOf(ManagerInterface::class, $manager);
    }

    /**
     * Test ManagerFactory::getManager() method with a supported class.
     */
    public function testGetManagerWithSupportedClass()
    {
        $manager = $this->getManagerMock();
        $this->managerFactory->addManager($manager);

        $manager = $this->managerFactory->getManager(Dummy::class);
        $this->assertInstanceOf(ManagerInterface::class, $manager);
    }

    /**
     * Test ManagerFactory::getManager() method with a supported object.
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
     * Test ManagerFactory::getManager() method with a supported class.
     */
    public function testGetManagerWithSupportedExtendedClass()
    {
        $manager = $this->getManagerMock();
        $this->managerFactory->addManager($manager);

        $manager = $this->managerFactory->getManager(ExtendedDummy::class);
        $this->assertInstanceOf(ManagerInterface::class, $manager);
    }

    /**
     * Test ManagerFactory::getManager() method with a supported object.
     */
    public function testGetManagerWithSupportedExtendedObject()
    {
        $manager = $this->getManagerMock();
        $this->managerFactory->addManager($manager);

        $dummy = new ExtendedDummy();
        $manager = $this->managerFactory->getManager($dummy);
        $this->assertInstanceOf(ManagerInterface::class, $manager);
    }

    /**
     * Test ManagerFactory::getManager() method with an unsupported class.
     * 
     * @expectedException \Boulzy\ManagerBundle\Exception\UnresolvedManagerException
     */
    public function testGetManagerWithUnsupportedClass()
    {
        $manager = $this->getManagerMock();
        $this->managerFactory->addManager($manager);

        $manager = $this->managerFactory->getManager(UnsupportedDummy::class);
    }

    /**
     * Test ManagerFactory::getManager() method with an unsupported object.
     * 
     * @expectedException \Boulzy\ManagerBundle\Exception\UnresolvedManagerException
     */
    public function testGetManagerWithUnsupportedObject()
    {
        $manager = $this->getManagerMock();
        $this->managerFactory->addManager($manager);

        $dummy = new UnsupportedDummy();
        $manager = $this->managerFactory->getManager($dummy);
    }

    /**
     * Get a manager mock for the Dummy model.
     * 
     * @return ManagerInterface
     */
    private function getManagerMock()
    {
        $manager = $this->createMock(ManagerInterface::class);
        $manager->method('getClass')
                ->willReturn(Dummy::class);

        return $manager;
    }
}

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

use Boulzy\ManagerBundle\Manager\Manager;
use Boulzy\ManagerBundle\Tests\Model\Dummy;
use Boulzy\ManagerBundle\Tests\Model\UnsupportedDummy;
use PHPUnit\Framework\TestCase;

/**
 * Test class for Manager abstract class.
 * 
 * @author Rémi Houdelette <https://github.com/B0ulzy>
 */
class ManagerTest extends TestCase
{
    /**
     * @var Manager
     */
    private $manager;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->manager = new DummyManager(Dummy::class);
    }

    /**
     * Test Manager::create() method.
     */
    public function testCreate()
    {
        $dummy = $this->manager->create();

        $this->assertInstanceOf(Dummy::class, $dummy);
    }

    /**
     * Test Manager::supports() method with a supported class.
     */
    public function testSupportsWithSupportedClass()
    {
        $dummy = new Dummy();

        $isSupported = $this->manager->supports($dummy);

        $this->assertTrue($isSupported);
    }

    /**
     * Test Manager::supports() method with an unsupported class.
     */
    public function testSupportsWithUnsupportedClass()
    {
        $dummy = new UnsupportedDummy();

        $isSupported = $this->manager->supports($dummy);

        $this->assertFalse($isSupported);
    }

    /**
     * Test Manager::supports() method with a supported object.
     */
    public function testSupportsWithSupportedObject()
    {
        $isSupported = $this->manager->supports(Dummy::class);

        $this->assertTrue($isSupported);
    }

    /**
     * Test Manager::supports() method with an unsupported object.
     */
    public function testSupportsWithUnsupportedObject()
    {
        $isSupported = $this->manager->supports(UnsupportedDummy::class);

        $this->assertFalse($isSupported);
    }

    /**
     * Test Manager::supports() method with an unexpected type as parameter.
     */
    public function testSupportsWithUnexpectedType()
    {
        $isSupported = $this->manager->supports(array());

        $this->assertfalse($isSupported);
    }

    /**
     * Test Manager::getClass() method.
     */
    public function testGetClass()
    {
        $class = $this->manager->getClass();

        $this->assertEquals(Dummy::class, $class);
    }
}

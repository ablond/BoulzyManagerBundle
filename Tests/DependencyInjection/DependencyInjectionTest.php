<?php

/*
 * This file is part of the BoulzyManagerBundle package.
 *
 * (c) Rémi Houdelette <https://github.com/B0ulzy>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Boulzy\ManagerBundle\DependencyInjection;

use Boulzy\ManagerBundle\DependencyInjection\BoulzyManagerExtension;
use Boulzy\ManagerBundle\Storage\Adapter\DoctrineOrmAdapter;
use Boulzy\ManagerBundle\Storage\Adapter\StorageAdapterInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DependencyInjectionTest extends TestCase
{
    public function testDependencyInjection()
    {
        $container = new ContainerBuilder();
        $extension = new BoulzyManagerExtension();

        $extension->load(array(), $container);
        $this->assertSame(DoctrineOrmAdapter::class, $container->getParameter('boulzy_manager.default_storage_adapter'));
    }

    public function testDependencyInjectionWithCustomDefaultStorageAdapter()
    {
        $storage = $this->createMock(StorageAdapterInterface::class);
        $container = new ContainerBuilder();
        $extension = new BoulzyManagerExtension();

        $extension->load(array('boulzy_manager' => array('default_storage_adapter' => get_class($storage))), $container);
        $this->assertSame(get_class($storage), $container->getParameter('boulzy_manager.default_storage_adapter'));
    }
}

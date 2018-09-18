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

use Boulzy\ManagerBundle\DependencyInjection\Compiler\DefaultStorageAdapterPass;
use Boulzy\ManagerBundle\Storage\Adapter\DoctrineOrmAdapter;
use Boulzy\ManagerBundle\Storage\Adapter\StorageAdapterInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

class DefaultStorageAdapterPassTest extends TestCase
{
    public function testProcess()
    {
        $em = $this->createMock(EntityManagerInterface::class);

        $container = new ContainerBuilder();
        $container
            ->register(DoctrineOrmAdapter::class)
            ->setClass(DoctrineOrmAdapter::class)
            ->setArgument('om', $em);

        $container->setParameter('boulzy_manager.default_storage_adapter', DoctrineOrmAdapter::class);

        $pass = new DefaultStorageAdapterPass();
        $pass->process($container);

        $storage = $container->get(StorageAdapterInterface::class);
        $this->assertInstanceOf(DoctrineOrmAdapter::class, $storage);
    }

    public function testProcessWithUnregisteredService()
    {
        $container = new ContainerBuilder();

        $container->setParameter('boulzy_manager.default_storage_adapter', DoctrineOrmAdapter::class);

        $this->expectException(ServiceNotFoundException::class);

        $pass = new DefaultStorageAdapterPass();
        $pass->process($container);
    }
}

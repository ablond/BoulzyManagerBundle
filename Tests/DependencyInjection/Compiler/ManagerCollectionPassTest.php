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

use Boulzy\ManagerBundle\BoulzyManagerBundle;
use Boulzy\ManagerBundle\Collection\ManagerCollection;
use Boulzy\ManagerBundle\DependencyInjection\Compiler\ManagerCollectionPass;
use Doctrine\Common\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Tests\Boulzy\ManagerBundle\Manager\Dummy1Manager;
use Tests\Boulzy\ManagerBundle\Model\Dummy1;

class ManagerCollectionPassTest extends TestCase
{
    public function testProcess()
    {
        $container = new ContainerBuilder();

        $container
            ->register(ManagerCollection::class)
            ->setClass(ManagerCollection::class)
            ->setPublic(true)
            ->addTag('boulzy_manager.collection')
        ;

        $om = $this->createMock(ObjectManager::class);

        $container
            ->register(Dummy1Manager::class)
            ->setClass(Dummy1Manager::class)
            ->setArgument('$om', new Definition(get_class($om)))
            ->addTag('boulzy_manager.manager');

        $pass = new ManagerCollectionPass();
        $pass->process($container);

        $managerCollection = $container->get(ManagerCollection::class);
        $this->assertInstanceOf(ManagerCollection::class, $managerCollection);

        $dummyManager = $managerCollection->get(Dummy1::class);
        $this->assertInstanceOf(Dummy1Manager::class, $dummyManager);
    }
}

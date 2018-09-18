<?php

/*
 * This file is part of the BoulzyManagerBundle package.
 *
 * (c) Rémi Houdelette <https://github.com/B0ulzy>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Boulzy\ManagerBundle;

use Boulzy\ManagerBundle\BoulzyManagerBundle;
use Boulzy\ManagerBundle\DependencyInjection\Compiler\DefaultStorageAdapterPass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BoulzyManagerBundleTest extends TestCase
{
    public function testBuild()
    {
        $bundle = new BoulzyManagerBundle();

        $this->assertInstanceOf(Bundle::class, $bundle);

        $container = new ContainerBuilder();
        $bundle->build($container);

        $passes = $container->getCompilerPassConfig()->getPasses();
        $passesClasses = array();
        foreach ($passes as $pass) {
            $passesClasses[] = get_class($pass);
        }

        $this->assertTrue(in_array(DefaultStorageAdapterPass::class, $passesClasses));
    }
}

<?php

/*
 * This file is part of the BoulzyManagerBundle package.
 *
 * (c) Rémi Houdelette <https://github.com/B0ulzy>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Boulzy\ManagerBundle\DependencyInjection\Compiler;

use Boulzy\ManagerBundle\Factory\ManagerFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Registers tagged managers into the manager factory.
 * 
 * @author Rémi Houdelette <b0ulzy.todo@gmail.com>
 */
class ManagerPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(ManagerFactory::class)) {
            return;
        }

        $definition = $container->findDefinition(ManagerFactory::class);

        $taggedServices = $container->findTaggedServiceIds('boulzy_manager.manager');
        $taggedServicesIds = array_keys($taggedServices);

        foreach ($taggedServicesIds as $id) {
            $definition->addMethodCall('addManager', array(new Reference($id)));
        }
    }
}
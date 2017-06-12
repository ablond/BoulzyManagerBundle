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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * A compiler pass to register tagged managers into the manager factory.
 * 
 * @author Rémi Houdelette <https://github.com/B0ulzy>
 */
class ManagerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        // always first check if the primary service is defined
        if (!$container->has('boulzy_manager.factory.manager_factory')) {
            return;
        }

        $definition = $container->findDefinition('boulzy_manager.factory.manager_factory');

        // find all service IDs with the app.mail_transport tag
        $taggedServices = $container->findTaggedServiceIds('boulzy_manager.manager');
        foreach ($taggedServices as $id => $tags) {
            // add the transport service to the ChainTransport service
            $definition->addMethodCall('addManager', array(new Reference($id)));
        }
    }
}
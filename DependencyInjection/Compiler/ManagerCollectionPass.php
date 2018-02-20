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

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Registers tagged managers into the manager factory and inject the default manager.
 *
 * @author Rémi Houdelette <b0ulzy.todo@gmail.com>
 */
class ManagerCollectionPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        // Get tagged collections IDs
        $taggedCollections = $container->findTaggedServiceIds('boulzy_manager.collection');
        $taggedCollectionIds = array_keys($taggedCollections);

        // Get tagged collections definitions
        $collectionDefinitions = array();
        foreach ($taggedCollectionIds as $taggedCollectionId) {
            $collectionDefinitions[] = $container->findDefinition($taggedCollectionId);
        }

        // Get tagged managers IDs
        $taggedManagers = $container->findTaggedServiceIds('boulzy_manager.manager');
        $taggedManagerIds = array_keys($taggedManagers);

        // For each registered manager, we add it to the registered collections
        foreach ($taggedManagerIds as $taggedManagerId) {
            foreach ($collectionDefinitions as $collectionDefinition) {
                $collectionDefinition->addMethodCall('add', array(new Reference($taggedManagerId)));
            }
        }
    }
}

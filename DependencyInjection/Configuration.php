<?php

/*
 * This file is part of the BoulzyManagerBundle package.
 *
 * (c) Rémi Houdelette <https://github.com/B0ulzy>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Boulzy\ManagerBundle\DependencyInjection;

use Boulzy\ManagerBundle\Storage\Adapter\DoctrineOrmAdapter;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Validates and merges configuration from the application configuration files.
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}.
 *
 * @author Rémi Houdelette <b0ulzy.todo@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /** {@inheritdoc} */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('boulzy_manager');

        $rootNode
            ->children()
                ->scalarNode('default_storage_adapter')
                    ->cannotBeEmpty()
                    ->defaultValue(DoctrineOrmAdapter::class)
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}

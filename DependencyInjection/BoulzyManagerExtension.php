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

use Boulzy\ManagerBundle\Collection\ManagerCollectionInterface;
use Boulzy\ManagerBundle\Manager\ManagerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Loads and manages the bundle configuration.
 * See {@link http://symfony.com/doc/current/cookbook/bundles/extension.html} for
 * more details.
 *
 * @author Rémi Houdelette <b0ulzy.todo@gmail.com>
 */
class BoulzyManagerExtension extends Extension
{
    /** {@inheritdoc} */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('boulzy_manager.default_storage_adapter', $config['default_storage_adapter']);

        $container->registerForAutoconfiguration(ManagerCollectionInterface::class)->addTag('boulzy_manager.collection');
        $container->registerForAutoconfiguration(ManagerInterface::class)->addTag('boulzy_manager.manager');

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}

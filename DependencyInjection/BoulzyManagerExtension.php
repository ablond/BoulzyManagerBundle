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

use Boulzy\ManagerBundle\Manager\ManagerInterface;
use Boulzy\ManagerBundle\Manager\DefaultManagerTrait;
use Boulzy\ManagerBundle\Util\ManagerUtil;
use Symfony\Component\Config\Definition\Exception\InvalidDefinitionException;
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
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->setDoctrineObjectManagerParameter($container, $config['db_driver']);
        $this->setDefaultManagerParameter($container, $config['default_manager']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * Set the doctrine object manager parameter according to the database driver
     * used.
     * 
     * @param ContainerBuilder $container
     * @param string $dbDriver
     * @throws InvalidDefinitionException The database driver is not supported.
     */
    private function setDoctrineObjectManagerParameter(ContainerBuilder $container, string $dbDriver)
    {
        switch($dbDriver) {
            case 'orm':
                $objectManagerId = 'doctrine.orm.entity_manager';
                break;
            case 'mongodb':
                $objectManagerId = 'doctrine_mongodb.odm.document_manager';
                break;
            case 'couchdb':
                $objectManagerId = 'doctrine_couchdb.odm.default_document_manager';
                break;
            case 'phpcr':
                $objectManagerId = 'doctrine_phpcr.odm.default_document_manager';
                break;
            default:
                throw new InvalidDefinitionException(sprintf(
                    'Unsupported driver "%s".',
                    $dbDriver
                ));
        }

        $container->setParameter('boulzy_manager.doctrine.object_manager', $objectManagerId);
    }

    /**
     * Set the default manager parameter.
     * 
     * @param ContainerBuilder $container
     * @param string $defaultManagerClass
     * @throws InvalidDefinitionException The default manager class is invalid.
     */
    private function setDefaultManagerParameter(ContainerBuilder $container, string $defaultManagerClass)
    {
        if (!($defaultManagerClass === ManagerInterface::class || is_subclass_of($defaultManagerClass, ManagerInterface::class))) {
            throw new InvalidDefinitionException(sprintf(
                'The default manager class must implement the %s class and use the %s trait.',
                ManagerInterface::class,
                DefaultManagerTrait::class
            ));
        }

        $container->setParameter('boulzy_manager.default_manager', $defaultManagerClass);
    }
}

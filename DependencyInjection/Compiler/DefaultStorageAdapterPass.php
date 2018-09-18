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

use Boulzy\ManagerBundle\Storage\Adapter\StorageAdapterInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * Sets an alias for the StorageAdapterInterface, providing a default adapter when using autowiring.
 *
 * @author Rémi Houdelette <b0ulzy.todo@gmail.com>
 */
class DefaultStorageAdapterPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     *
     * @throws ServiceNotFoundException The service provided as default storage provider does not exist
     */
    public function process(ContainerBuilder $container)
    {
        $storageAdapterId = $container->getParameter('boulzy_manager.default_storage_adapter');

        // Checks if the service with the given ID does exist.
        if (!$container->has($storageAdapterId)) {
            throw new ServiceNotFoundException($storageAdapterId);
        }

        $container->setAlias(StorageAdapterInterface::class, $storageAdapterId);
    }
}

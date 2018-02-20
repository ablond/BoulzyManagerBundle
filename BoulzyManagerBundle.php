<?php

/*
 * This file is part of the BoulzyManagerBundle package.
 *
 * (c) Rémi Houdelette <https://github.com/B0ulzy>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Boulzy\ManagerBundle;

use Boulzy\ManagerBundle\DependencyInjection\Compiler\ManagerCollectionPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Bundle definition.
 *
 * @author Rémi Houdelette <b0ulzy.todo@gmail.com>
 */
class BoulzyManagerBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        // Adds the registered manager to the factory
        $container->addCompilerPass(new ManagerCollectionPass());
    }
}

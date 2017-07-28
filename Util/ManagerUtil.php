<?php

/*
 * This file is part of the BoulzyManagerBundle package.
 * 
 * (c) Rémi Houdelette <https://github.com/B0ulzy>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Boulzy\ManagerBundle\Util;

use Boulzy\ManagerBundle\Manager\ManagerInterface;

/**
 * Provides useful methods to handle managers.
 * 
 * @author Rémi Houdelette <b0ulzy.todo@gmail.com>
 */
class ManagerUtil
{
    /**
     * Checks if a manager use the 
     * 
     * @param ManagerInterface $manager
     * @return bool
     */
    static public function isDefaultManager(ManagerInterface $manager): bool
    {
        return in_array('Boulzy\ManagerBundle\Manager\DefaultManagerTrait', class_uses($manager));
    }
}

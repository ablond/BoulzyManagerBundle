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

/**
 * A class to help manipulate classes and objects.
 *
 * @author Rémi Houdelette <b0ulzy.todo@gmail.com>
 *
 * @deprecated this class should not be used outside of this bundle as it will be removed soon
 */
class ClassHelper
{
    public static function getClass($object)
    {
        $class = is_object($object) ? get_class($object) : $object;

        if (class_exists('\\Doctrine\\Common\\Util\\ClassUtils')) {
            $class = call_user_func(array('\\Doctrine\\Common\\Util\\ClassUtils', 'getRealClass'), $class);
        }

        return $class;
    }
}

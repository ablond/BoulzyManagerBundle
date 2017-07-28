<?php

/*
 * This file is part of the BoulzyManagerBundle package.
 *
 * (c) Rémi Houdelette <https://github.com/B0ulzy>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Boulzy\ManagerBundle\Manager;

/**
 * Base class for default managers.
 * 
 * @author Rémi Houdelette <b0ulzy.todo@gmail.com>
 */
class DefaultManager extends Manager
{
    use DefaultManagerTrait;
}

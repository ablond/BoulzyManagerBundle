<?php

/*
 * This file is part of the BoulzyManagerBundle package.
 *
 * (c) Rémi Houdelette <https://github.com/B0ulzy>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Boulzy\ManagerBundle\Exception;

/**
 * This exception is thrown when a manager cannot be resolved for a class.
 * This probably means that you're trying to find a manager for an unsuitable class.
 *  
 * @author Rémi Houdelette <b0ulzy.todo@gmail.com>
 */
class UnresolvedManagerException extends \LogicException
{
    const MESSAGE = 'No manager could be resolved for class "%s".';

    public function __construct(string $classname, int $code = 0, \Throwable $previous = null) {
        $message = sprintf(self::MESSAGE, $classname);

        parent::__construct($message, $code, $previous);
    }
}
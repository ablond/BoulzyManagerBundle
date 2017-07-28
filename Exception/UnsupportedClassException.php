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
 * This exception is thrown when a manager has to deal with an unsupported class.
 * This probably means there's something to fix in your code.
 * 
 * @author Rémi Houdelette <b0ulzy.todo@gmail.com>
 */
class UnsupportedClassException extends \LogicException
{
    const MESSAGE = 'The class "%s" is not supported by the manager "%s".';

    public function __construct(string $classname, string $managerClassname, int $code = 0, \Throwable $previous = null) {
        $message = sprintf(self::MESSAGE, $classname, $managerClassname);

        parent::__construct($message, $code, $previous);
    }
}

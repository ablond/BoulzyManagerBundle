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
 * This exception is thrown when no unique manager can be defined for a class.
 * You should call the manager you want directly instead of calling it through
 * a factory.
 * 
 * @author Rémi Houdelette <https://github.com/B0ulzy>
 */
class NonUniqueManagerException extends \RuntimeException
{
    const MESSAGE = 'An unique manager could not be resolved for class "%s".';

    public function __construct(string $classname, int $code = 0, \Throwable $previous = null) {
        $message = sprintf(self::MESSAGE, $classname);

        parent::__construct($message, $code, $previous);
    }
}

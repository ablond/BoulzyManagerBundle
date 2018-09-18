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
 * Exception thrown when no manager can be found for an object.
 *
 * @author Rémi Houdelette <b0ulzy.todo@gmail.com>
 */
class NotManagedObjectException extends \Exception
{
    const MESSAGE = 'No manager found for class "%s".';

    /** @var string */
    private $class;

    /**
     * NotManagedObjectException constructor.
     *
     * @param string          $class
     * @param \Throwable|null $previous
     * @param int             $code
     */
    public function __construct(string $class, \Throwable $previous = null, int $code = 0)
    {
        $this->class = $class;

        parent::__construct(\sprintf(self::MESSAGE, $class), $code, $previous);
    }

    /**
     * Returns the concerned class.
     *
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }
}

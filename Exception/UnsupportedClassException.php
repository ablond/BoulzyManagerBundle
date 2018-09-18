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
 * Exception thrown when a manager is dealing with an object it doesn't support.
 *
 * @author Rémi Houdelette <b0ulzy.todo@gmail.com>
 */
class UnsupportedClassException extends \Exception
{
    const MESSAGE = 'Unsupported class "%s". Expecting an instance of "%s".';

    /** @var string */
    private $expectedClass;

    /** @var string */
    private $actualClass;

    /**
     * UnsupportedObjectException constructor.
     *
     * @param string          $expectedClass
     * @param string          $actualClass
     * @param \Throwable|null $previous
     * @param int             $code
     */
    public function __construct(string $expectedClass, string $actualClass, \Throwable $previous = null, int $code = 0)
    {
        $this->expectedClass = $expectedClass;
        $this->actualClass = $actualClass;

        parent::__construct(\sprintf(self::MESSAGE, $expectedClass, $actualClass), $code, $previous);
    }

    /**
     * Returns the expected model class.
     *
     * @return string
     */
    public function getExpectedClass(): string
    {
        return $this->expectedClass;
    }

    /**
     * Returns the actual class used.
     *
     * @return string
     */
    public function getActualClass(): string
    {
        return $this->actualClass;
    }
}

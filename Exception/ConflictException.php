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

use Boulzy\ManagerBundle\Manager\ManagerInterface;

/**
 * Exception thrown when two managers are found to manage an object.
 *
 * @author Rémi Houdelette <b0ulzy.todo@gmail.com>
 */
class ConflictException extends \Exception
{
    const MESSAGE = 'Two managers were found for the same object "%s": "%s" and "%s". You should have only one manager by object class.';

    /** @var string */
    private $manager1;

    /** @var string */
    private $manager2;

    /** @var string */
    private $object;

    /**
     * ConflictException constructor.
     *
     * @param ManagerInterface $manager1 The class of the first manager
     * @param ManagerInterface $manager2 The class of the second manager
     * @param string           $object   The class of the model
     * @param \Throwable|null  $previous
     * @param int              $code
     */
    public function __construct(ManagerInterface $manager1, ManagerInterface $manager2, string $object, \Throwable $previous = null, int $code = 0)
    {
        $this->manager1 = $manager1;
        $this->manager2 = $manager2;
        $this->object = $object;

        parent::__construct(sprintf(self::MESSAGE, $object, \get_class($manager1), \get_class($manager2)), $code, $previous);
    }

    /**
     * Returns the first manager class.
     *
     * @return ManagerInterface
     */
    public function getManager1(): ManagerInterface
    {
        return $this->manager1;
    }

    /**
     * Returns the second manager class.
     *
     * @return ManagerInterface
     */
    public function getManager2(): ManagerInterface
    {
        return $this->manager2;
    }

    /**
     * Returns the object class.
     *
     * @return string
     */
    public function getObject(): string
    {
        return $this->object;
    }
}

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
 * Provides a base for managers.
 * 
 * @author Rémi Houdelette <https://github.com/B0ulzy>
 */
abstract class Manager implements ManagerInterface
{
    /**
     * @var string
     */
    protected $class;

    public function __construct(string $class)
    {
        $this->class = $class;
    }

    /**
     * {@inheritDoc}
     */
    public function create()
    {
        $class = $this->getClass();

        return new $class();
    }

    /**
     * {@inheritDoc}
     */
    public function supports($object): bool
    {
        $class = is_object($object) ? get_class($object) : $object;
        $supportedClass = $this->getClass();

        return $class === $supportedClass || is_subclass_of($class, $supportedClass);
    }

    /**
     * {@inheritDoc}
     */
    public function getClass(): string
    {
        return $this->class;
    }
}

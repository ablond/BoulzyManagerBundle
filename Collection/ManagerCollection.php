<?php

/*
 * This file is part of the BoulzyManagerBundle package.
 *
 * (c) Rémi Houdelette <https://github.com/B0ulzy>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Boulzy\ManagerBundle\Collection;

use Boulzy\ManagerBundle\Exception\ConflictException;
use Boulzy\ManagerBundle\Exception\NotManagedModelException;
use Boulzy\ManagerBundle\Manager\ManagerInterface;

/**
 * A factory for managers.
 *
 * @author Rémi Houdelette <b0ulzy.todo@gmail.com>
 */
class ManagerCollection implements ManagerCollectionInterface
{
    /**
     * @var array
     */
    private $managers;

    /**
     * ManagerCollection constructor.
     */
    public function __construct()
    {
        $this->managers = array();
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     * @throws ConflictException
     * @throws NotManagedModelException
     */
    public function get($model): ManagerInterface
    {
        $class = $this->getModelClass($model);

        // Checks if the manager is already registered
        if (isset($this->managers[$class])) {
            return $this->managers[$class];
        }

        // Checks if the model is supported by another manager
        $managerFound = null;
        /** @var ManagerInterface $manager */
        foreach ($this->managers as $manager) {
            // If multiple managers support the model, we throw a ConflictException
            if ($manager->supports($model) && null !== $managerFound) {
                throw new ConflictException($managerFound, $manager, $class);
            } elseif ($manager->supports($model)) {
                $managerFound = $manager;
            }
        }

        // If no manager is found, we throw a NotManagedModelException
        if (null === $managerFound) {
            throw new NotManagedModelException($class);
        }

        // To prevent the search again of a manager, we register the manager found as the class manager.
        $this->managers[$class] = $managerFound;

        return $managerFound;
    }

    /**
     * {@inheritdoc}
     *
     * @throws ConflictException a manager for the supported model already exists
     */
    public function add(ManagerInterface $manager)
    {
        $class = $manager->getClass();

        // If the model class is not registered yet or if the new manager extends the existing manager, we register the
        // new manager
        if (!isset($this->managers[$class]) || is_subclass_of($manager, get_class($this->managers[$class]))) {
            $this->managers[$class] = $manager;

            return;
        }

        // If the already existing class extends the new manager, we do nothing
        if (is_a($this->managers[$class], get_class($manager))) {
            return;
        }

        throw new ConflictException($this->managers[$class], $manager, $class);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     */
    public function exists($model): bool
    {
        try {
            $this->get($model);
        } catch (NotManagedModelException $e) {
            return false;
        } catch (ConflictException $e) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function remove(ManagerInterface $manager)
    {
        $key = array_search($manager, $this->managers);

        if ($key) {
            unset($this->managers[$key]);
        }
    }

    /**
     * Returns the model class.
     *
     * @param object|string $model the model object / classname to evaluate
     *
     * @return string
     *
     * @throws \InvalidArgumentException the `$model` parameter is neither an object or a class name
     */
    private function getModelClass($model): string
    {
        if ((is_string($model) && !class_exists($model)) || (!is_string($model) && !is_object($model))) {
            throw new \InvalidArgumentException('The `$model` parameter must be a class name or an object.');
        }

        $class = is_string($model) ? $model : get_class($model);

        if (class_exists('\Doctrine\Common\Util\ClassUtils')) {
            $class = call_user_func(array('\Doctrine\Common\Util\ClassUtils', 'getRealClass'), $class);
        }

        return $class;
    }
}

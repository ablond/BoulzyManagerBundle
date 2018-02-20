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
 * Exception thrown when two managers are found to manage a model.
 *
 * @author Rémi Houdelette <b0ulzy.todo@gmail.com>
 */
class ConflictException extends \Exception
{
    const MESSAGE = 'Two managers were found for the same model "%s": "%s" and "%s". You should have only one manager by model.';

    /**
     * @var string
     */
    private $manager1;

    /**
     * @var string
     */
    private $manager2;

    /**
     * @var string
     */
    private $model;

    /**
     * ConflictException constructor.
     *
     * @param ManagerInterface $manager1 the class of the first manager
     * @param ManagerInterface $manager2 the class of the second manager
     * @param string           $model    the class of the model
     * @param \Throwable|null  $previous
     * @param int              $code
     */
    public function __construct(ManagerInterface $manager1, ManagerInterface $manager2, string $model, \Throwable $previous = null, int $code = 0)
    {
        $this->manager1 = $manager1;
        $this->manager2 = $manager2;
        $this->model = $model;

        parent::__construct(sprintf(self::MESSAGE, $model, get_class($manager1), get_class($manager2)), $code, $previous);
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
     * Returns the model class.
     *
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }
}

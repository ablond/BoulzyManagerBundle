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

use Doctrine\Common\Persistence\ObjectManager;

/**
 * Trait to be used by default managers.
 * 
 * @author Rémi Houdelette <b0ulzy.todo@gmail.com>
 */
trait DefaultManagerTrait
{
    /**
     * @var string
     */
    private $class;

    /**
     * Returns the class managed by this manager.
     * 
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * Sets the class managed by this manager.
     * 
     * @param string $class
     * @return $this
     */
    public function setClass(string $class): DefaultManager
    {
        $this->class = $class;
        
        if (property_exists($this, 'repository')
            && property_exists($this, 'om')
            && $this->om instanceof ObjectManager
        ) {
            $this->repository = $this->om->getRepository($this->class);
        }

        return $this;
    }
}

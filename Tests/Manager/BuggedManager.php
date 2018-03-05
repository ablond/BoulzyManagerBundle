<?php

/*
 * This file is part of the BoulzyManagerBundle package.
 *
 * (c) Rémi Houdelette <https://github.com/B0ulzy>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Boulzy\ManagerBundle\Manager;

use Boulzy\ManagerBundle\Manager\DoctrineManager;
use Doctrine\Common\Persistence\ObjectManager;
use Psr\Log\LoggerInterface;
use Tests\Boulzy\ManagerBundle\Model\Dummy1;

class BuggedManager extends DoctrineManager
{
    private $logger;

    public function __construct(ObjectManager $om, LoggerInterface $logger)
    {
        parent::__construct($om);
        $this->logger = $logger;
    }

    public function getClass(): string
    {
        return Dummy1::class;
    }

    protected function save($object)
    {
        throw new \Exception('Database is down.');
    }

    protected function doDelete($object)
    {
        throw new \Exception('Database is down.');
    }

    protected function onCreateFailed($object, \Exception $e = null)
    {
        $this->logger->alert($e->getMessage());
    }

    protected function onUpdateFailed($object, \Exception $e = null)
    {
        $this->logger->alert($e->getMessage());
    }

    protected function onDeleteFailed($object, \Exception $e = null)
    {
        $this->logger->alert($e->getMessage());
    }
}

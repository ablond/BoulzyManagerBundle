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

use Boulzy\ManagerBundle\Manager\Manager;
use Tests\Boulzy\ManagerBundle\Model\Dummy;

class DummyManager extends Manager
{
    public function getClass(): string
    {
        return Dummy::class;
    }

    protected function onPreCreate(Dummy $dummy)
    {
        $dummy->onPreCreateCalled = true;
    }

    protected function onPostCreate(Dummy $dummy)
    {
        $dummy->onPostCreateCalled = true;
    }

    protected function onCreateFailed(Dummy $dummy)
    {
        $dummy->onCreateFailedCalled = true;
    }

    protected function onPreUpdate(Dummy $dummy)
    {
        $dummy->onPreUpdateCalled = true;
    }

    protected function onPostUpdate(Dummy $dummy)
    {
        $dummy->onPostUpdateCalled = true;
    }

    protected function onUpdateFailed(Dummy $dummy)
    {
        $dummy->onUpdateFailedCalled = true;
    }

    protected function onPreDelete(Dummy $dummy)
    {
        $dummy->onPreDeleteCalled = true;
    }

    protected function onDeleteFailed(Dummy $dummy)
    {
        $dummy->onDeleteFailedCalled = true;
    }
}

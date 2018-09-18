<?php

/*
 * This file is part of the BoulzyManagerBundle package.
 *
 * (c) Rémi Houdelette <https://github.com/B0ulzy>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Boulzy\ManagerBundle\Model;

class Dummy
{
    public $onPreCreateCalled = false;
    public $onPostCreateCalled = false;
    public $onCreateFailedCalled = false;
    public $onPreUpdateCalled = false;
    public $onPostUpdateCalled = false;
    public $onUpdateFailedCalled = false;
    public $onPreDeleteCalled = false;
    public $onDeleteFailedCalled = false;
}

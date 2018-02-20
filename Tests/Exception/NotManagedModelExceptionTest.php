<?php

/*
 * This file is part of the BoulzyManagerBundle package.
 *
 * (c) Rémi Houdelette <https://github.com/B0ulzy>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Boulzy\ManagerBundle\Exception;

use Boulzy\ManagerBundle\Exception\NotManagedModelException;
use PHPUnit\Framework\TestCase;
use Tests\Boulzy\ManagerBundle\Model\Dummy1;

class NotManagedModelExceptionTest extends TestCase
{
    public function testGettersAndSetters()
    {
        $notManagedModelException = new NotManagedModelException(Dummy1::class);

        $this->assertSame(Dummy1::class, $notManagedModelException->getClass());
    }
}

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

use Boulzy\ManagerBundle\Exception\ConflictException;
use Boulzy\ManagerBundle\Manager\ManagerInterface;
use PHPUnit\Framework\TestCase;
use Tests\Boulzy\ManagerBundle\Model\Dummy1;

class ConflictExceptionTest extends TestCase
{
    public function testGettersAndSetters()
    {
        $manager1 = $this->createMock(ManagerInterface::class);
        $manager2 = $this->createMock(ManagerInterface::class);

        $conflictException = new ConflictException(
            $manager1,
            $manager2,
            Dummy1::class
        );

        $this->assertSame($manager1, $conflictException->getManager1());
        $this->assertSame($manager2, $conflictException->getManager2());
        $this->assertSame(Dummy1::class, $conflictException->getModel());
    }
}

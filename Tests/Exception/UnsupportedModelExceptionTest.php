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

use Boulzy\ManagerBundle\Exception\UnsupportedModelException;
use PHPUnit\Framework\TestCase;
use Tests\Boulzy\ManagerBundle\Model\Dummy1;
use Tests\Boulzy\ManagerBundle\Model\Dummy2;

class UnsupportedModelExceptionTest extends TestCase
{
    public function testGettersAndSetters()
    {
        $unsupportedModelException = new UnsupportedModelException(Dummy1::class, Dummy2::class);

        $this->assertSame(Dummy1::class, $unsupportedModelException->getExpectedClass());
        $this->assertSame(Dummy2::class, $unsupportedModelException->getActualClass());
    }
}

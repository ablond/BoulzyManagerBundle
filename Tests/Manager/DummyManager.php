<?php

/*
 * This file is part of the BoulzyManagerBundle package.
 *
 * (c) Rémi Houdelette <https://github.com/B0ulzy>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Boulzy\ManagerBundle\Tests\Manager;

use Boulzy\ManagerBundle\Manager\Manager;

/**
 * Dummy manager used to test the Manager abstract class.
 * 
 * @author Rémi Houdelette <https://github.com/B0ulzy>
 */
class DummyManager extends Manager
{
    public function delete($object) {}
    public function get($id) {}
    public function getAll(): array {}
    public function getBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array {}
    public function getOneBy(array $criteria) {}
    public function save($object) {}
}

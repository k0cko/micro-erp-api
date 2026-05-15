<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class SoftDeleteRepository extends ServiceEntityRepository
{
    public function withDeleted(callable $callback): mixed
    {
        $filters = $this->getEntityManager()->getFilters();
        try {
            $filters->disable('soft_delete_filter');
            $result = $callback();
        } finally {
            $filters->enable('soft_delete_filter');
        }

        return $result;
    }
}
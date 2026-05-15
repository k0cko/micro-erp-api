<?php

namespace App\Repository;

use App\Entity\Warehouse;
use Doctrine\Persistence\ManagerRegistry;

class WarehouseRepository extends SoftDeleteRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Warehouse::class);
    }

    public function existsByName(string $name, ?int $id = null): bool
    {
        $query = $this->createQueryBuilder('w')
            ->select('1')
            ->where('LOWER(w.name) = LOWER(:name)')
            ->setParameter('name', $name)
            ->setMaxResults(1);

        if ($id !== null) {
            $query
                ->andWhere('w.id != :id')
                ->setParameter('id', $id);
        }

        return $query
            ->getQuery()
            ->getOneOrNullResult() !== null;
    }

    //    /**
    //     * @return Warehouse[] Returns an array of Warehouse objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('w.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Warehouse
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

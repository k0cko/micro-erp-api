<?php

namespace App\Repository;

use App\Entity\Warehouse;
use App\Entity\WarehouseProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WarehouseProduct>
 */
class WarehouseProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WarehouseProduct::class);
    }

    public function createPaginatedQueryBuilder(Warehouse $warehouse): QueryBuilder
    {
        return $this->createQueryBuilder('wp')
            ->andWhere('wp.warehouse = :warehouse')
            ->setParameter('warehouse', $warehouse)
            ->orderBy('wp.id', 'DESC');
    }

    //    /**
    //     * @return WarehouseProduct[] Returns an array of WarehouseProduct objects
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

    //    public function findOneBySomeField($value): ?WarehouseProduct
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

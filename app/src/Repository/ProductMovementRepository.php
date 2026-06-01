<?php

namespace App\Repository;

use App\Entity\ProductMovement;
use App\Entity\Warehouse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductMovement>
 */
class ProductMovementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductMovement::class);
    }

    public function createPaginatedQueryBuilder(Warehouse $warehouse): QueryBuilder
     {
        return $this->createQueryBuilder('pm')
            ->andWhere('pm.warehouse = :warehouse')
            ->setParameter('warehouse', $warehouse)
            ->orderBy('pm.id', 'DESC');
    }

    //    /**
    //     * @return ProductMovement[] Returns an array of ProductMovement objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ProductMovement
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

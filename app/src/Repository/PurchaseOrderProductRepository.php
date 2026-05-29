<?php

namespace App\Repository;

use App\Entity\PurchaseOrder;
use App\Entity\PurchaseOrderProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PurchaseOrderProduct>
 */
class PurchaseOrderProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PurchaseOrderProduct::class);
    }

    public function deleteRemoved(PurchaseOrder $purchaseOrder, array $excludeProductIds): void
    {
        if (!$excludeProductIds) {
            return;
        }
        
        $this->createQueryBuilder('pop')
            ->delete()
            ->andWhere('pop.purchaseOrder = :purchaseOrder')
            ->setParameter('purchaseOrder', $purchaseOrder)
            ->andWhere('pop.product NOT IN (:excludeProductIds)')
            ->setParameter('excludeProductIds', $excludeProductIds)
            ->getQuery()
            ->execute();
    }

//    /**
//     * @return PurchaseOrderProduct[] Returns an array of PurchaseOrderProduct objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PurchaseOrderProduct
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

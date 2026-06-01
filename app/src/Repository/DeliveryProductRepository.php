<?php

namespace App\Repository;

use App\Entity\Delivery;
use App\Entity\DeliveryProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DeliveryProduct>
 */
class DeliveryProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeliveryProduct::class);
    }

    public function deleteRemoved(Delivery $delivery, array $excludeProductIds): void
    {
        if (!$excludeProductIds) {
            return;
        }
        
        $this->createQueryBuilder('dp')
            ->delete()
            ->andWhere('dp.delivery = :delivery')
            ->setParameter('delivery', $delivery)
            ->andWhere('dp.product NOT IN (:excludeProductIds)')
            ->setParameter('excludeProductIds', $excludeProductIds)
            ->getQuery()
            ->execute();
    }
     
    public function createPaginatedQueryBuilder(Delivery $delivery): QueryBuilder
    {
        return $this->createQueryBuilder('dp')
            ->andWhere('dp.delivery = :delivery')
            ->setParameter('delivery', $delivery)
            ->orderBy('dp.id', 'DESC');
    }

    //    /**
    //     * @return DeliveryProduct[] Returns an array of DeliveryProduct objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?DeliveryProduct
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

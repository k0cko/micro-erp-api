<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function existsByName(string $name, ?int $id = null): bool
    {
        $query = $this->createQueryBuilder('p')
            ->select('1')
            ->where('LOWER(p.name) = LOWER(:name)')
            ->setParameter('name', $name)
            ->setMaxResults(1);

        if ($id !== null) {
            $query
                ->andWhere('p.id != :id')
                ->setParameter('id', $id);
        }
        
        return $query
            ->getQuery()
            ->getOneOrNullResult() !== null;
    }

    public function createPaginatedQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC');
    }

    //    /**
    //     * @return Product[] Returns an array of Product objects
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

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserRepository extends SoftDeleteRepository implements PasswordUpgraderInterface, UserLoaderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function existsByUsername(string $username, ?int $id = null): bool
    {
        $query = $this->createQueryBuilder('u')
            ->select('1')
            ->where('u.username = :username')
            ->setParameter('username', $username)
            ->setMaxResults(1);

        if ($id !== null) {
            $query
                ->andWhere('u.id != :id')
                ->setParameter('id', $id);
        }

        return $query
            ->getQuery()
            ->getOneOrNullResult() !== null;
    }

    public function loadUserByIdentifier(string $identifier): ?UserInterface
    {
        return $this->withDeleted(fn() => $this->createQueryBuilder('u')
                ->where('u.username = :username')
                ->setParameter('username', $identifier)
                ->getQuery()
                ->getOneOrNullResult());
    }

    public function createPaginatedQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.id', 'DESC');
    }

    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

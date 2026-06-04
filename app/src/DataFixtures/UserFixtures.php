<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Enum\UserRole;
use App\Service\User\PasswordHasher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const ADMIN_USER_REFERENCE = 'admin-user';

    public function __construct(
        private readonly PasswordHasher $passwordHasher,
    ) {}

    public function load(ObjectManager $manager): void
    {
        $superAdminUser = User::createSuperAdmin(
            'super_admin_user',
            $this->passwordHasher->hash('password'),
            'super_admin_user',
            'super_admin_user',
        );

        $adminUser = new User(
            'admin_user',
            $this->passwordHasher->hash('password'),
            'admin_user',
            'admin_user',
            UserRole::Admin,
        );

        $workerUser = new User(
            'worker_user',
            $this->passwordHasher->hash('password'),
            'worker_user',
            'worker_user',
            UserRole::Worker,
        );

        $deletedUser = new User(
            'deleted_user',
            $this->passwordHasher->hash('password'),
            'deleted_user',
            'deleted_user',
            UserRole::Worker,
        );
        $deletedUser->softDelete();

        $manager->persist($superAdminUser);
        $manager->persist($adminUser);
        $manager->persist($workerUser);
        $manager->persist($deletedUser);
        $manager->flush();

        $this->setReference(self::ADMIN_USER_REFERENCE, $adminUser);
    }
}

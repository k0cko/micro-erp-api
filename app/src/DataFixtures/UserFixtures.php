<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Service\User\PasswordHasher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function __construct(
        private readonly PasswordHasher $passwordHasher,
    ) {}

    public function load(ObjectManager $manager): void
    {
        $user = User::createSuperAdmin(
            'test',
            $this->passwordHasher->hash('password'),
            'test',
            'test',
        );

        $deletedUser = User::createSuperAdmin(
            'deleted_user',
            $this->passwordHasher->hash('password'),
            'deleted',
            'deleted',
        );
        $deletedUser->softDelete();

        $manager->persist($user);
        $manager->persist($deletedUser);
        $manager->flush();
    }
}

<?php

namespace App\Security;

use App\Entity\User;
use App\Service\User\PasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class SymfonyPasswordHasher implements PasswordHasher
{
    public function __construct(
        private readonly PasswordHasherFactoryInterface $passwordHasherFactory,
    ) {}

    public function hash(string $password): string
    {
        $passwordHasher = $this->passwordHasherFactory->getPasswordHasher(User::class);
        return $passwordHasher->hash($password);
    }
}
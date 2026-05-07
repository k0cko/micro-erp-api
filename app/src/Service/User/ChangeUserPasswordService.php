<?php

namespace App\Service\User;

use App\DTO\User\ChangeUserPasswordInput;
use App\Entity\User;
use App\Exception\OldPasswordMismatchException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class ChangeUserPasswordService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManagerInterface,
        private readonly UserPasswordHasherInterface $passwordHasherInterface
    ) {}

    public function execute(ChangeUserPasswordInput $input, User $user): void
    {
        if (!$this->passwordHasherInterface->isPasswordValid($user, $input->oldPassword)) {
            throw OldPasswordMismatchException::create();
        }

        $hashedPassword = $this->passwordHasherInterface->hashPassword($user, $input->newPassword);
        $user->changePassword($hashedPassword);

        $this->entityManagerInterface->flush();
    }
}
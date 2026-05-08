<?php

namespace App\Service\User;

use App\DTO\User\CreateUserInput;
use App\Entity\User;
use App\Exception\DuplicateResourceException;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class CreateUserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {}

    public function execute(CreateUserInput $input): int
    {
        if ($this->userRepository->existsByUsername($input->username)) {
            throw DuplicateResourceException::forField('User', 'username', $input->username);
        }

        /** @todo Avoid "limbo" object by extracting a PasswordHasher service wrapper */
        $hashedPassword = $this->passwordHasher->hashPassword(new User('', '', '', ''), $input->password);

        $user = User::create($input, $hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user->getId();
    }
}
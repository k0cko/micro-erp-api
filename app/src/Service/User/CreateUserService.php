<?php

namespace App\Service\User;

use App\DTO\User\CreateUserInput;
use App\DTO\User\UserResponse;
use App\Entity\User;
use App\Enum\UserRole;
use App\Exception\DuplicateResourceException;
use App\Mapper\User\UserResponseMapper;
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

    public function execute(CreateUserInput $input): UserResponse
    {
        if ($this->userRepository->existsByUsername($input->username)) {
            throw DuplicateResourceException::forField('User', 'username', $input->username);
        }

        /** @todo Avoid "limbo" object by extracting a PasswordHasher service wrapper */
        $hashedPassword = $this->passwordHasher->hashPassword(new User('', '', '', '', UserRole::Worker), $input->password);

        $user = User::create($input, $hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return UserResponseMapper::map($user);
    }
}
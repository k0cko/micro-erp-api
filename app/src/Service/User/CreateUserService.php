<?php

namespace App\Service\User;

use App\DTO\User\CreateUserInput;
use App\DTO\User\UserResponse;
use App\Entity\User;
use App\Exception\DuplicateResourceException;
use App\Mapper\User\UserResponseMapper;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

final class CreateUserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly PasswordHasher $passwordHasher,
    ) {}

    public function execute(CreateUserInput $input): UserResponse
    {
        if ($this->userRepository->existsByUsername($input->username)) {
            throw DuplicateResourceException::forField('User', 'username', $input->username);
        }

        $user = User::create($input, $this->passwordHasher->hash($input->password));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return UserResponseMapper::map($user);
    }
}
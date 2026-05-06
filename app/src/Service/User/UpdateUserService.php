<?php

namespace App\Service\User;

use App\DTO\User\UpdateUserInput;
use App\DTO\User\UserResponse;
use App\Entity\User;
use App\Mapper\User\UserResponseMapper;
use Doctrine\ORM\EntityManagerInterface;

final class UpdateUserService
{
    public function __construct(
        private readonly UserResponseMapper $userResponseMapper,
        private readonly EntityManagerInterface $entityManagerInterface,
    ) {}

    public function execute(User $user, UpdateUserInput $input): UserResponse
    {
        $user->update($input->firstName, $input->lastName);

        $this->entityManagerInterface->flush();

        return $this->userResponseMapper->mapToResponse($user);
    }
}
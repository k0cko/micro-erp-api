<?php

namespace App\Service\User;

use App\Mapper\User\UserResponseMapper;
use App\Repository\UserRepository;
use App\Entity\User;

final class ListUserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserResponseMapper $userResponseMapper
    ) {}

    /** @return User[] */
    public function execute(): array
    {
        $users = [];
        foreach ($this->userRepository->findAll() as $user) {
            $users[] = $this->userResponseMapper->mapToResponse($user);
        }

        return $users; 
    }
}
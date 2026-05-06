<?php

namespace App\Mapper\User;

use App\DTO\User\UserResponse;
use App\Entity\User;

final readonly class UserResponseMapper
{
    public function mapToResponse(User $user): UserResponse
    {
        return new UserResponse(
            username: $user->getUsername(),
            firstName: $user->getFirstName(),
            lastName: $user->getLastName(),
            createdAt: $user->getCreatedAt(),
            updatedAt: $user->getUpdatedAt(),
        );
    }
}
<?php

namespace App\Mapper\User;

use App\DTO\User\UserResponse;
use App\Entity\User;
use App\Enum\UserRole;

final readonly class UserResponseMapper
{
    public static function map(User $user): UserResponse
    {
        return new UserResponse(
            username: $user->getUsername(),
            firstName: $user->getFirstName(),
            lastName: $user->getLastName(),
            role: $user->getRole()->label(),
            createdAt: $user->getCreatedAt(),
            updatedAt: $user->getUpdatedAt(),
            deletedAt: $user->getDeletedAt(),
        );
    }
}
<?php

namespace App\DTO\User;

use App\Enum\UserRole;

final readonly class UserResponse
{
    public function __construct(
        public string $username,
        public string $firstName,
        public string $lastName,
        public string $role,
        public \DateTimeImmutable $createdAt,
        public \DateTimeImmutable $updatedAt,
        public \DateTimeImmutable $deletedAt,
    ) {}
}
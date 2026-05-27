<?php

namespace App\DTO\ProductMovement;

final readonly class ProductMovementResponse
{
    public function __construct(
        public int $id,
        public string $productName,
        public string $type,
        public \DateTimeImmutable $createdAt,
        public \DateTimeImmutable $updatedAt,
    ) {}
}
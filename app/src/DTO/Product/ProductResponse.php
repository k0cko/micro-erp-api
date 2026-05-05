<?php

namespace App\DTO\Product;

final readonly class ProductResponse
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $description,
        public \DateTimeImmutable $createdAt,
        public \DateTimeImmutable $updatedAt,
    ) {}
}
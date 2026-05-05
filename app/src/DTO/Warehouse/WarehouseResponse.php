<?php

namespace App\DTO\Warehouse;

final readonly class WarehouseResponse
{
    public function __construct(
        public int $id,
        public string $name,
        public \DateTimeImmutable $createdAt,
        public \DateTimeImmutable $updatedAt,
    ) {}
}
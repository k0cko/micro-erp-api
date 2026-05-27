<?php

namespace App\DTO\Warehouse;

final readonly class WarehouseProductResponse
{
    public function __construct(
        public int $id,
        public string $productName,
        public int $quantity,
    ) {}
}
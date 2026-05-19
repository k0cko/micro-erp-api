<?php

namespace App\DTO\PurchaseOrder;

final readonly class PurchaseOrderProductResponse
{
    public function __construct(
        public readonly string $product,
        public readonly string $quantity,
    ) {}
}

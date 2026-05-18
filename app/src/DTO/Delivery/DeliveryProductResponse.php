<?php

namespace App\DTO\Delivery;

final readonly class DeliveryProductResponse
{
    public function __construct(
        public readonly string $product,
        public readonly string $quantity,
    ) {}
}

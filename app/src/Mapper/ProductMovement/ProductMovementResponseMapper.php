<?php

namespace App\Mapper\ProductMovement;

use App\DTO\ProductMovement\ProductMovementResponse;
use App\Entity\ProductMovement;

final readonly class ProductMovementResponseMapper
{
    public static function map(ProductMovement $productMovement): ProductMovementResponse
    {
        return new ProductMovementResponse(
            id: $productMovement->getId(),
            productName: $productMovement->getProduct()->getName(),
            type: $productMovement->getType()->label(),
            createdAt: $productMovement->getCreatedAt(),
            updatedAt: $productMovement->getUpdatedAt(),
        );
    }
}
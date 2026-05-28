<?php

namespace App\Mapper\Product;

use App\DTO\Product\ProductResponse;
use App\Entity\Product;

final readonly class ProductResponseMapper
{
    public static function map(Product $product): ProductResponse
    {
        return new ProductResponse(
            id: $product->getId(),
            name: $product->getName(),
            description: $product->getDescription(),
            createdAt: $product->getCreatedAt(),
            updatedAt: $product->getUpdatedAt(),
        );
    }
}
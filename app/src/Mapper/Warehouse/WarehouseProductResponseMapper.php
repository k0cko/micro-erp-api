<?php

namespace App\Mapper\Warehouse;

use App\DTO\Warehouse\WarehouseProductResponse;
use App\Entity\WarehouseProduct;

final readonly class WarehouseProductResponseMapper
{
    public static function mapToResponse(WarehouseProduct $warehouseProduct): WarehouseProductResponse
    {
        return new WarehouseProductResponse(
            id: $warehouseProduct->getId(),
            productName: $warehouseProduct->getProduct()->getName(),
            quantity: $warehouseProduct->getQuantity(),
        );
    }
}
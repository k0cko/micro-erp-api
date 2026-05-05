<?php

namespace App\Mapper\Warehouse;

use App\DTO\Warehouse\WarehouseResponse;
use App\Entity\Warehouse;

final readonly class WarehouseResponseMapper
{
    public function mapToResponse(Warehouse $warehouse): WarehouseResponse
    {
        return new WarehouseResponse(
            id: $warehouse->getId(),
            name: $warehouse->getName(),
            createdAt: $warehouse->getCreatedAt(),
            updatedAt: $warehouse->getUpdatedAt(),
        );
    }
}
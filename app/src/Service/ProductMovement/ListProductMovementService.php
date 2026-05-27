<?php

namespace App\Service\ProductMovement;

use App\DTO\ProductMovement\ProductMovementResponse;
use App\Entity\Warehouse;
use App\Mapper\ProductMovement\ProductMovementResponseMapper;
use App\Repository\ProductMovementRepository;

class ListProductMovementService
{
    /** @return ProductMovementResponse[] */
    public function execute(Warehouse $warehouse): array
    {
        $productMovementResponse = [];
        foreach ($warehouse->getProductMovements() as $productMovement) {
            $productMovementResponse[] = ProductMovementResponseMapper::mapToResponse($productMovement);
        }

        return $productMovementResponse;
    }
}
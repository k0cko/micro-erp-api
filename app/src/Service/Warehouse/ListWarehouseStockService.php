<?php

namespace App\Service\Warehouse;

use App\DTO\Warehouse\WarehouseProductResponse;
use App\Entity\Warehouse;
use App\Mapper\Warehouse\WarehouseProductResponseMapper;

class ListWarehouseStockService
{
 
    /** @return WarehouseProductResponse[] */
    public function execute(Warehouse $warehouse): array
    {
        $warehouseProductResponse = [];
        foreach ($warehouse->getWarehouseProducts() as $warehouseProduct) {
            $warehouseProductResponse[] = WarehouseProductResponseMapper::map($warehouseProduct);
        }

        return $warehouseProductResponse;
    }
}
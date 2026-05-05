<?php

namespace App\Service\Warehouse;

use App\Mapper\Warehouse\WarehouseResponseMapper;
use App\Repository\WarehouseRepository;
use App\DTO\Warehouse\WarehouseResponse;

final class ListWarehouseService
{
    public function __construct(
        private readonly WarehouseRepository $warehouseRepository,
        private readonly WarehouseResponseMapper $warehouseResponseMapper,
    ) {}

    /** @return WarehouseResponse[] */
    public function execute(): array
    {
        $warehouseResponses = [];
        foreach ($this->warehouseRepository->findAll() as $warehouse) {
            $warehouseResponses[] = $this->warehouseResponseMapper->mapToResponse($warehouse);
        }

        return $warehouseResponses;
    }

}
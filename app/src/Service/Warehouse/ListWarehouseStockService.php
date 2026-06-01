<?php

namespace App\Service\Warehouse;

use App\DTO\Pagination\PaginatedResult;
use App\Entity\Warehouse;
use App\Entity\WarehouseProduct;
use App\Mapper\Warehouse\WarehouseProductResponseMapper;
use App\Repository\WarehouseProductRepository;
use App\Service\Pagination\PagePaginatorService;

final class ListWarehouseStockService
{
    public function __construct(
        private readonly WarehouseProductRepository $warehouseProductRepository,
        private readonly PagePaginatorService $paginator,
    ) {}

    public function execute(Warehouse $warehouse, int $page, int $limit): PaginatedResult
    {
        return $this->paginator->paginate(
            $this->warehouseProductRepository->createPaginatedQueryBuilder($warehouse),
            fn(WarehouseProduct $warehouseProduct) => WarehouseProductResponseMapper::map($warehouseProduct),
            $page,
            $limit,
        );
    }
}
<?php

namespace App\Service\Warehouse;

use App\DTO\Pagination\PaginatedResult;
use App\Mapper\Warehouse\WarehouseResponseMapper;
use App\Repository\WarehouseRepository;
use App\Entity\Warehouse;
use App\Service\Pagination\PagePaginatorService;

final class ListWarehouseService
{
    public function __construct(
        private readonly WarehouseRepository $warehouseRepository,
        private readonly PagePaginatorService $paginator,
    ) {}

    public function execute(int $page, int $limit): PaginatedResult
    {
        return $this->paginator->paginate(
            $this->warehouseRepository->createPaginatedQueryBuilder(),
            fn(Warehouse $warehouse) => WarehouseResponseMapper::map($warehouse),
            $page,
            $limit,
        );
    }
}
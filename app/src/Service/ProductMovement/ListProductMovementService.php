<?php

namespace App\Service\ProductMovement;

use App\DTO\Pagination\PaginatedResult;
use App\Entity\ProductMovement;
use App\Entity\Warehouse;
use App\Mapper\ProductMovement\ProductMovementResponseMapper;
use App\Repository\ProductMovementRepository;
use App\Service\Pagination\PagePaginatorService;

class ListProductMovementService
{
    public function __construct(
        private readonly ProductMovementRepository $productMovementRepository,
        private readonly PagePaginatorService $paginator,
    ) {}

    public function execute(Warehouse $warehouse, int $page, int $limit): PaginatedResult
    {
        return $this->paginator->paginate(
            $this->productMovementRepository->createPaginatedQueryBuilder($warehouse),
            fn(ProductMovement $productMovement) => ProductMovementResponseMapper::map($productMovement),
            $page,
            $limit,
        );
    }
}
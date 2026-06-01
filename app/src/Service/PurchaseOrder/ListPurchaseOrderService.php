<?php

namespace App\Service\PurchaseOrder;

use App\DTO\Pagination\PaginatedResult;
use App\Entity\PurchaseOrder;
use App\Mapper\PurchaseOrder\PurchaseOrderResponseMapper;
use App\Repository\PurchaseOrderRepository;
use App\Service\Pagination\PagePaginatorService;

final class ListPurchaseOrderService
{
    public function __construct(
        private readonly PurchaseOrderRepository $purchaseOrderRepository,
        private readonly PagePaginatorService $paginator,
    ) {}

    public function execute(int $page, int $limit): PaginatedResult
    {
        return $this->paginator->paginate(
            $this->purchaseOrderRepository->createPaginatedQueryBuilder(),
            fn(PurchaseOrder $purchaseOrder) => PurchaseOrderResponseMapper::map($purchaseOrder),
            $page,
            $limit,
        );
    }
}

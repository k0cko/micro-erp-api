<?php

namespace App\Service\PurchaseOrder;

use App\DTO\Pagination\PaginatedResult;
use App\Entity\PurchaseOrder;
use App\Entity\PurchaseOrderProduct;
use App\Mapper\PurchaseOrder\PurchaseOrderProductResponseMapper;
use App\Repository\PurchaseOrderProductRepository;
use App\Service\Pagination\PagePaginatorService;

final class ListPurchaseOrderProductService
{
    public function __construct(
        private readonly PurchaseOrderProductRepository $purchaseOrderProductRepository,
        private readonly PagePaginatorService $paginator,
    ) {}

    public function execute(PurchaseOrder $purchaseOrder, int $page, int $limit): PaginatedResult
    {
        return $this->paginator->paginate(
            $this->purchaseOrderProductRepository->createPaginatedQueryBuilder($purchaseOrder),
            fn(PurchaseOrderProduct $purchaseOrderProduct) => PurchaseOrderProductResponseMapper::map($purchaseOrderProduct),
            $page,
            $limit,
        );
    }
}

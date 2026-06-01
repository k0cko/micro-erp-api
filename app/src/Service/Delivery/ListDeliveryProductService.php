<?php

namespace App\Service\Delivery;

use App\Repository\DeliveryProductRepository;
use App\DTO\Pagination\PaginatedResult;
use App\Entity\Delivery;
use App\Entity\DeliveryProduct;
use App\Mapper\Delivery\DeliveryProductResponseMapper;
use App\Service\Pagination\PagePaginatorService;

final class ListDeliveryProductService
{
    public function __construct(
        private readonly DeliveryProductRepository $deliveryProductRepository,
        private readonly PagePaginatorService $paginator,
    ) {}

    public function execute(Delivery $delivery, int $page, int $limit): PaginatedResult
    {
        return $this->paginator->paginate(
            $this->deliveryProductRepository->createPaginatedQueryBuilder($delivery),
            fn(DeliveryProduct $deliveryProduct) => DeliveryProductResponseMapper::map($deliveryProduct),
            $page,
            $limit,
        );
    }
}

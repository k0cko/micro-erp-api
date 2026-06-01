<?php

namespace App\Service\Delivery;

use App\DTO\Pagination\PaginatedResult;
use App\Entity\Delivery;
use App\Mapper\Delivery\DeliveryResponseMapper;
use App\Repository\DeliveryRepository;
use App\Service\Pagination\PagePaginatorService;

final class ListDeliveryService
{
    public function __construct(
        private readonly DeliveryRepository $deliveryRepository,
        private readonly PagePaginatorService $paginator,
    ) {}

    public function execute(int $page, int $limit): PaginatedResult
    {
        return $this->paginator->paginate(
            $this->deliveryRepository->createPaginatedQueryBuilder(),
            fn(Delivery $delivery) => DeliveryResponseMapper::map($delivery),
            $page,
            $limit,
        );
    }
}

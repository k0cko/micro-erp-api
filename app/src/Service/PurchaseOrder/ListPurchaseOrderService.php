<?php

namespace App\Service\PurchaseOrder;

use App\DTO\PurchaseOrder\PurchaseOrderResponse;
use App\Mapper\PurchaseOrder\PurchaseOrderResponseMapper;
use App\Repository\PurchaseOrderRepository;

final class ListPurchaseOrderService
{
    public function __construct(
        private readonly PurchaseOrderRepository $purchaseOrderRepository
    ) {}

    /** @return PurchaseOrderResponse[] */
    public function execute()
    {
        $purchaseOrders = [];
        foreach ($this->purchaseOrderRepository->findAll() as $purchaseOrder) {
            $purchaseOrders[] = PurchaseOrderResponseMapper::map($purchaseOrder);
        }

        return $purchaseOrders;
    }
}

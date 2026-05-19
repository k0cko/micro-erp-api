<?php

namespace App\Mapper\PurchaseOrder;

use App\DTO\PurchaseOrder\PurchaseOrderResponse;
use App\Entity\PurchaseOrder;

final readonly class PurchaseOrderResponseMapper
{
    public static function map(PurchaseOrder $purchaseOrder): PurchaseOrderResponse
    {
        return new PurchaseOrderResponse(
            number: sprintf('%s-%06d', new \DateTimeImmutable()->format('Y'), $purchaseOrder->getId()),
            status: $purchaseOrder->getStatus()->label(),
            contractor: $purchaseOrder->getContractor()->getName(),
            warehouse: $purchaseOrder->getWarehouse()->getName(),
            date: $purchaseOrder->getDate(),
            createdAt: $purchaseOrder->getCreatedAt(),
            updatedAt: $purchaseOrder->getUpdatedAt(),
        );
    }
}

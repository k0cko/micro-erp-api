<?php

namespace App\Mapper\PurchaseOrder;

use App\DTO\PurchaseOrder\PurchaseOrderProductResponse;
use App\Entity\PurchaseOrderProduct;

final readonly class PurchaseOrderProductResponseMapper
{
    public static function map(PurchaseOrderProduct $purchaseOrderProduct): PurchaseOrderProductResponse
    {
        return new PurchaseOrderProductResponse(
            product: $purchaseOrderProduct->getProduct()->getName(),
            quantity: $purchaseOrderProduct->getQuantity(),
        );
    }
}

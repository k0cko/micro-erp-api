<?php

namespace App\DTO\PurchaseOrder;

use Symfony\Component\Validator\Constraints as Assert;
use App\DTO\PurchaseOrder\PurchaseOrderProductInput;
use Symfony\Component\Serializer\Attribute\SerializedName;

final readonly class PurchaseOrderProductsInput
{
    /** @param PurchaseOrderProductInput[] $purchaseOrderProducts */
    public function __construct(
        #[Assert\Valid]
        #[Assert\Count(
            min: 1,
            minMessage: 'You must add at least one product to the delivery'
        )]
        #[SerializedName('purchase_order_products')]
        public readonly array $purchaseOrderProducts = [],
    ) {}
}
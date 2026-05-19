<?php

namespace App\DTO\PurchaseOrder;

use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class PurchaseOrderProductInput
{
    public function __construct(
        #[Assert\NotBlank(message: 'Product can not be blank.')]
        #[SerializedName('product_id')]
        public readonly int $productId,
        #[Assert\GreaterThan(
            value: 0,
            message: 'Quantity can not be lass than or equal to 0'
        )]
        public readonly int $quantity
    ) {}
}
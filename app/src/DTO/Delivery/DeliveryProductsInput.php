<?php

namespace App\DTO\Delivery;

use Symfony\Component\Validator\Constraints as Assert;
use App\DTO\Delivery\DeliveryProductInput;
use Symfony\Component\Serializer\Attribute\SerializedName;

final readonly class DeliveryProductsInput
{
    /** @param DeliveryProductInput[] $deliveryProducts */
    public function __construct(
        #[Assert\Valid]
        #[Assert\Count(
            min: 1,
            minMessage: 'You must add at least one product to the delivery'
        )]
        #[SerializedName('delivery_products')]
        public readonly array $deliveryProducts = [],
    ) {}
}
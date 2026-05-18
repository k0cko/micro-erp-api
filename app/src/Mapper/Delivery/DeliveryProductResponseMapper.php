<?php

namespace App\Mapper\Delivery;

use App\DTO\Delivery\DeliveryProductResponse;
use App\Entity\DeliveryProduct;

final readonly class DeliveryProductResponseMapper
{
    public static function map(DeliveryProduct $deliveryProduct): DeliveryProductResponse
    {
        return new DeliveryProductResponse(
            product: $deliveryProduct->getProduct()->getName(),
            quantity: $deliveryProduct->getQuantity(),
        );
    }
}

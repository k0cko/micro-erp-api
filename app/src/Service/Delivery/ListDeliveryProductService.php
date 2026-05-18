<?php

namespace App\Service\Delivery;

use App\Repository\DeliveryProductRepository;
use App\DTO\Delivery\DeliveryProductResponse;
use App\Entity\Delivery;
use App\Mapper\Delivery\DeliveryProductResponseMapper;

final class ListDeliveryProductService
{
    public function __construct(
        private readonly DeliveryProductRepository $deliveryProductRepository
    ) {}

    /** @return DeliveryProductResponse[] */
    public function execute(Delivery $delivery)
    {
        $deliveryProducts = [];
        foreach ($this->deliveryProductRepository->findBy(['delivery' => $delivery]) as $deliveryProduct) {
            $deliveryProducts[] = DeliveryProductResponseMapper::map($deliveryProduct);
        }

        return $deliveryProducts;
    }
}

<?php

namespace App\Service\Delivery;

use App\DTO\Delivery\DeliveryResponse;
use App\Mapper\Delivery\DeliveryResponseMapper;
use App\Repository\DeliveryRepository;

final class ListDeliveryService
{
    public function __construct(
        private readonly DeliveryRepository $deliveryRepository
    ) {}

    /** @return DeliveryResponse[] */
    public function execute()
    {
        $deliveries = [];
        foreach ($this->deliveryRepository->findAll() as $delivery) {
            $deliveries[] = DeliveryResponseMapper::map($delivery);
        }

        return $deliveries;
    }
}

<?php

namespace App\Service\Delivery;

use App\DTO\Delivery\DeliveryResponse;
use App\Entity\Delivery;
use App\Mapper\Delivery\DeliveryResponseMapper;
use Doctrine\ORM\EntityManagerInterface;

final class CancelDeliveryService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function execute(Delivery $delivery): DeliveryResponse
    {
        $delivery->cancel();
        $deliveryProducts = $delivery->getDeliveryProducts();

        foreach ($deliveryProducts as $deliveryProduct) {
            $deliveryProduct->markAsCancelled();
        }

        $this->entityManager->flush();

        return DeliveryResponseMapper::map($delivery);
    }
}
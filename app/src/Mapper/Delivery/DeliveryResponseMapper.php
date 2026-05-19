<?php

namespace App\Mapper\Delivery;

use App\DTO\Delivery\DeliveryResponse;
use App\Entity\Delivery;

final readonly class DeliveryResponseMapper
{
    public static function map(Delivery $delivery): DeliveryResponse
    {
        return new DeliveryResponse(
            number: sprintf('%s-%06d', new \DateTimeImmutable()->format('Y'), $delivery->getId()),
            status: $delivery->getStatus()->label(),
            contractor: $delivery->getContractor()->getName(),
            warehouse: $delivery->getWarehouse()->getName(),
            date: $delivery->getDate(),
            createdAt: $delivery->getCreatedAt(),
            updatedAt: $delivery->getUpdatedAt(),
        );
    }
}

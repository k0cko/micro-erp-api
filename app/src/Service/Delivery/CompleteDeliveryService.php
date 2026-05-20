<?php

namespace App\Service\Delivery;

use App\DTO\Delivery\DeliveryResponse;
use App\Entity\Delivery;
use App\Mapper\Delivery\DeliveryResponseMapper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

final class CompleteDeliveryService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly EventDispatcherInterface $dispatcher,
    ) {}

    public function execute(Delivery $delivery): DeliveryResponse
    {
        $delivery->complete();

        foreach ($delivery->releaseEvents() as $event) {
            $this->dispatcher->dispatch($event);
        }

        $this->entityManager->flush();

        return DeliveryResponseMapper::map($delivery);
    }
}
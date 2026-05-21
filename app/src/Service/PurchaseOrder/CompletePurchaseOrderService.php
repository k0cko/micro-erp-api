<?php

namespace App\Service\PurchaseOrder;

use App\DTO\PurchaseOrder\PurchaseOrderResponse;
use App\Entity\PurchaseOrder;
use App\Mapper\PurchaseOrder\PurchaseOrderResponseMapper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

final class CompletePurchaseOrderService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly EventDispatcherInterface $dispatcher,
    ) {}

    public function execute(PurchaseOrder $purchaseOrder): PurchaseOrderResponse
    {
        $purchaseOrder->complete();

        foreach ($purchaseOrder->releaseEvents() as $event) {
            $this->dispatcher->dispatch($event);
        }

        $this->entityManager->flush();

        return PurchaseOrderResponseMapper::map($purchaseOrder);
    }
}
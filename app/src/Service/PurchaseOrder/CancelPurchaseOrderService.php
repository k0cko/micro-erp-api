<?php

namespace App\Service\PurchaseOrder;

use App\DTO\PurchaseOrder\PurchaseOrderResponse;
use App\Entity\PurchaseOrder;
use App\Mapper\PurchaseOrder\PurchaseOrderResponseMapper;
use Doctrine\ORM\EntityManagerInterface;

final class CancelPurchaseOrderService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function execute(PurchaseOrder $purchaseOrder): PurchaseOrderResponse
    {
        $purchaseOrder->cancel();
        $purchaseOrderProducts = $purchaseOrder->getPurchaseOrderProducts();

        foreach ($purchaseOrderProducts as $purchaseOrderProduct) {
            $purchaseOrderProduct->markAsCancelled();
        }

        $this->entityManager->flush();

        return PurchaseOrderResponseMapper::map($purchaseOrder);
    }
}
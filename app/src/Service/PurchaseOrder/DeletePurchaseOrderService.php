<?php

namespace App\Service\PurchaseOrder;

use App\Entity\PurchaseOrder;
use Doctrine\ORM\EntityManagerInterface;

final class DeletePurchaseOrderService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function execute(PurchaseOrder $purchaseOrder): void
    {
        $purchaseOrder->delete();
        $this->entityManager->remove($purchaseOrder);
        $this->entityManager->flush();
    }
}

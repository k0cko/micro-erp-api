<?php

namespace App\Service\PurchaseOrder;

use App\Entity\PurchaseOrder;
use App\Entity\PurchaseOrderProduct;
use Doctrine\ORM\EntityManagerInterface;

final class DeletePurchaseOrderProductService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function execute(PurchaseOrder $purchaseOrder, PurchaseOrderProduct $purchaseOrderProduct): void
    {
        if ($purchaseOrder->getId() !== $purchaseOrderProduct->getPurchaseOrder()->getId()) {
            throw new \LogicException('Purchase order product does not belong to this purchase order.');
        }
        
        $purchaseOrderProduct->delete();
        $this->entityManager->remove($purchaseOrderProduct);
        $this->entityManager->flush();
    }
}

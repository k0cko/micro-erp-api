<?php

namespace App\Service\PurchaseOrder;

use App\DTO\PurchaseOrder\PurchaseOrderProductResponse;
use App\Entity\PurchaseOrder;
use App\Entity\PurchaseOrderProduct;
use App\Mapper\PurchaseOrder\PurchaseOrderProductResponseMapper;
use Doctrine\ORM\EntityManagerInterface;

final class PreparePurchaseOrderProductService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function execute(PurchaseOrder $purchaseOrder, PurchaseOrderProduct $purchaseOrderProduct): PurchaseOrderProductResponse
    {
        if ($purchaseOrder->getId() !== $purchaseOrderProduct->getPurchaseOrder()->getId()) {
            throw new \LogicException('Purchase order product does not belong to this purchase order.');
        }

        $purchaseOrderProduct->markAsPrepared();

        $this->entityManager->flush();

        return PurchaseOrderProductResponseMapper::map($purchaseOrderProduct);
    }
}
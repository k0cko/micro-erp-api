<?php

namespace App\Service\PurchaseOrder;

use App\Entity\PurchaseOrder;
use App\Entity\PurchaseOrderProduct;
use App\Enum\InquiryStatus;
use App\Exception\InvalidStatusException;
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
        if (in_array($purchaseOrder->getStatus(), [InquiryStatus::Completed, InquiryStatus::Cancelled])) {
            throw InvalidStatusException::forInvalidAction('Purchase order products', 'deleted', 'purchase order', [InquiryStatus::Completed->label(), InquiryStatus::Cancelled->label()]);
        }

        $this->entityManager->remove($purchaseOrderProduct);
        $this->entityManager->flush();
    }
}

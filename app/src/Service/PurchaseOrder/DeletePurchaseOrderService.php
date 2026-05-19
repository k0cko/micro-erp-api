<?php

namespace App\Service\PurchaseOrder;

use App\Entity\PurchaseOrder;
use App\Enum\InquiryStatus;
use App\Exception\InvalidStatusException;
use Doctrine\ORM\EntityManagerInterface;

final class DeletePurchaseOrderService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function execute(PurchaseOrder $purchaseOrder): void
    {
        if ($purchaseOrder->getStatus() !== InquiryStatus::Draft) {
            throw InvalidStatusException::create('Purchase order', 'deleted', InquiryStatus::Draft->value);
        }
        
        $this->entityManager->remove($purchaseOrder);
        $this->entityManager->flush();
    }
}

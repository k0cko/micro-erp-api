<?php

namespace App\Service\Delivery;

use App\Entity\Delivery;
use App\Entity\DeliveryProduct;
use App\Enum\InquiryStatus;
use App\Exception\InvalidStatusException;
use Doctrine\ORM\EntityManagerInterface;

final class DeleteDeliveryProductService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function execute(Delivery $delivery, DeliveryProduct $deliveryProduct): void
    {
        if ($delivery->getId() !== $deliveryProduct->getDelivery()->getId()) {
            throw new \LogicException('Delivery product does not belong to this delivery.');
        }
        if (in_array($delivery->getStatus(), [InquiryStatus::Completed, InquiryStatus::Cancelled])) {
            throw InvalidStatusException::forInvalidAction('Delivery products', 'deleted', 'delivery', [InquiryStatus::Completed->label(), InquiryStatus::Cancelled->label()]);
        }

        $this->entityManager->remove($deliveryProduct);
        $this->entityManager->flush();
    }
}

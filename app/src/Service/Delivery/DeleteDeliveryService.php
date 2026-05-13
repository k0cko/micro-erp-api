<?php

namespace App\Service\Delivery;

use App\Entity\Delivery;
use App\Enum\InquiryStatus;
use App\Exception\InvalidStatusException;
use Doctrine\ORM\EntityManagerInterface;

final class DeleteDeliveryService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function execute(Delivery $delivery): void
    {
        if ($delivery->getStatus() !== InquiryStatus::Draft) {
            throw InvalidStatusException::create('Delivery', 'deleted', InquiryStatus::Draft->value);
        }
        
        $this->entityManager->remove($delivery);
        $this->entityManager->flush();
    }
}

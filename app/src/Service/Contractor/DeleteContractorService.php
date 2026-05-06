<?php

namespace App\Service\Contractor;

use App\Entity\Contractor;
use App\Exception\ResourceInUseException;
use Doctrine\ORM\EntityManagerInterface;

final class DeleteContractorService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManagerInterface
    ) {}

    public function execute(Contractor $contractor): void
    {
        if (!$contractor->getPurchaseOrders()->isEmpty() || !$contractor->getDeliveries()->isEmpty()) {
            throw ResourceInUseException::forEntity('Contractor', 'Deliveries or Order');
        }

        $this->entityManagerInterface->remove($contractor);
        $this->entityManagerInterface->flush();
    }
}
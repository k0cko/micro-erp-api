<?php

namespace App\Service\Delivery;

use App\Entity\Delivery;
use Doctrine\ORM\EntityManagerInterface;

final class DeleteDeliveryService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function execute(Delivery $delivery): void
    {   
        $delivery->delete();
        $this->entityManager->remove($delivery);
        $this->entityManager->flush();
    }
}

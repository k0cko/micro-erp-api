<?php

namespace App\Service\Warehouse;

use App\Entity\Warehouse;
use App\Exception\ResourceInUseException;
use Doctrine\ORM\EntityManagerInterface;

final class DeleteWarehouseService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function execute(Warehouse $warehouse): void
    {
        if (!$warehouse->getProductMovements()->isEmpty()) {
            throw ResourceInUseException::forEntity('Warehouse', 'ProductMovement');
        }
    
        $this->entityManager->remove($warehouse);
        $this->entityManager->flush();
    }
}
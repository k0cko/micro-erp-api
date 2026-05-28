<?php

namespace App\Service\Warehouse;

use App\DTO\Warehouse\WarehouseInput;
use App\DTO\Warehouse\WarehouseResponse;
use App\Entity\Warehouse;
use App\Exception\DuplicateResourceException;
use App\Mapper\Warehouse\WarehouseResponseMapper;
use App\Repository\WarehouseRepository;
use Doctrine\ORM\EntityManagerInterface;

final class UpdateWarehouseService
{
    public function __construct(
        private readonly WarehouseRepository $warehouseRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function execute(WarehouseInput $input, Warehouse $warehouse): WarehouseResponse
    {
        if ($this->warehouseRepository->existsByName($input->name, $warehouse->getId())) {
            throw DuplicateResourceException::forField('Warehouse', 'name', $input->name);
        }

        $warehouse->update($input->name);

        $this->entityManager->flush();

        return WarehouseResponseMapper::map($warehouse);
    }
}
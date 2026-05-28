<?php

namespace App\Service\Warehouse;

use App\DTO\Warehouse\WarehouseInput;
use App\DTO\Warehouse\WarehouseResponse;
use App\Repository\WarehouseRepository;
use App\Entity\Warehouse;
use App\Exception\DuplicateResourceException;
use App\Mapper\Warehouse\WarehouseResponseMapper;
use Doctrine\ORM\EntityManagerInterface;

final class CreateWarehouseService
{
    public function __construct(
        private readonly WarehouseRepository $warehouseRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function execute(WarehouseInput $input): WarehouseResponse
    {
        if ($this->warehouseRepository->existsByName($input->name)) {
            throw DuplicateResourceException::forField('Warehouse', 'name', $input->name);
        }

        $warehouse = Warehouse::create($input->name);

        $this->entityManager->persist($warehouse);
        $this->entityManager->flush();

        return WarehouseResponseMapper::map($warehouse);
    }

}
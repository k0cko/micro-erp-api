<?php

namespace App\Service\Warehouse;

use App\DTO\Product\ProductInput;
use App\DTO\Product\ProductResponse;
use App\DTO\Warehouse\WarehouseInput;
use App\DTO\Warehouse\WarehouseResponse;
use App\Entity\Product;
use App\Entity\Warehouse;
use App\Exception\DuplicateResourceException;
use App\Mapper\Product\ProductResponseMapper;
use App\Mapper\Warehouse\WarehouseResponseMapper;
use App\Repository\ProductRepository;
use App\Repository\WarehouseRepository;
use Doctrine\ORM\EntityManagerInterface;

final class UpdateWarehouseService
{
    public function __construct(
        private readonly WarehouseRepository $warehouseRepository,
        private readonly EntityManagerInterface $entityManagerInterface,
        private readonly WarehouseResponseMapper $warehouseResponseMapper
    ) {}

    public function execute(WarehouseInput $input, Warehouse $warehouse): WarehouseResponse
    {
        if ($this->warehouseRepository->existsByName($input->name, $warehouse->getId())) {
            throw DuplicateResourceException::forField('Warehouse', 'warehouse', $input->name);
        }

        $warehouse->update($input->name);

        $this->entityManagerInterface->flush();

        return $this->warehouseResponseMapper->mapToResponse($warehouse);
    }
}
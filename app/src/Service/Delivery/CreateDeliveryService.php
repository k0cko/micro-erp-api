<?php

namespace App\Service\Delivery;

use App\DTO\Delivery\DeliveryInput;
use App\DTO\Delivery\DeliveryResponse;
use App\Entity\Delivery;
use App\Entity\User;
use App\Mapper\Delivery\DeliveryResponseMapper;
use App\Repository\ContractorRepository;
use App\Repository\WarehouseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class CreateDeliveryService
{
    public function __construct(
        private readonly ContractorRepository $contractorRepository,
        private readonly WarehouseRepository $warehouseRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function execute(DeliveryInput $input, User $user): DeliveryResponse
    {
        $contractor = $this->contractorRepository->find($input->contractorId) ?? throw new NotFoundHttpException('Contractor not found.');
        $warehouse = $this->warehouseRepository->find($input->warehouseId) ?? throw new NotFoundHttpException('Warehouse not found.');

        $delivery = Delivery::create($input, $user, $contractor, $warehouse);

        $this->entityManager->persist($delivery);
        $this->entityManager->flush();

        return DeliveryResponseMapper::map($delivery);
    }
}

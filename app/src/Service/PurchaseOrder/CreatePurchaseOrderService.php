<?php

namespace App\Service\PurchaseOrder;

use App\DTO\PurchaseOrder\PurchaseOrderInput;
use App\Entity\PurchaseOrder;
use App\Entity\User;
use App\Repository\ContractorRepository;
use App\Repository\WarehouseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class CreatePurchaseOrderService
{
    public function __construct(
        private readonly ContractorRepository $contractorRepository,
        private readonly WarehouseRepository $warehouseRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function execute(PurchaseOrderInput $input, User $user): int
    {
        $contractor = $this->contractorRepository->find($input->contractorId) ?? throw new NotFoundHttpException('Contractor not found.');
        $warehouse = $this->warehouseRepository->find($input->warehouseId) ?? throw new NotFoundHttpException('Warehouse not found.');

        $purchaseOrder = PurchaseOrder::create($input, $user, $contractor, $warehouse);

        $this->entityManager->persist($purchaseOrder);
        $this->entityManager->flush();

        return $purchaseOrder->getId();
    }
}

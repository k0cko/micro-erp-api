<?php

namespace App\Service\PurchaseOrder;

use App\DTO\PurchaseOrder\PurchaseOrderInput;
use App\DTO\PurchaseOrder\PurchaseOrderResponse;
use App\Entity\PurchaseOrder;
use App\Mapper\PurchaseOrder\PurchaseOrderResponseMapper;
use App\Repository\ContractorRepository;
use App\Repository\WarehouseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class UpdatePurchaseOrderService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ContractorRepository $contractorRepository,
        private readonly WarehouseRepository $warehouseRepository,
    ) {}

    public function execute(PurchaseOrder $purchaseOrder, PurchaseOrderInput $input): PurchaseOrderResponse
    {
        $contractor = $this->contractorRepository->find($input->contractorId) ?? throw new NotFoundHttpException('Contractor not found.');
        $warehouse = $this->warehouseRepository->find($input->warehouseId) ?? throw new NotFoundHttpException('Warehouse not found.');

        $purchaseOrder->update($input, $contractor, $warehouse);

        $this->entityManager->flush();

        return PurchaseOrderResponseMapper::map($purchaseOrder);
    }
}

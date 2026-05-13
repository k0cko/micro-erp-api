<?php

namespace App\Service\Delivery;

use App\DTO\Delivery\DeliveryInput;
use App\DTO\Delivery\DeliveryResponse;
use App\Entity\Delivery;
use App\Enum\InquiryStatus;
use App\Exception\InvalidStatusException;
use App\Mapper\Delivery\DeliveryResponseMapper;
use App\Repository\ContractorRepository;
use App\Repository\WarehouseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class UpdateDeliveryService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ContractorRepository $contractorRepository,
        private readonly WarehouseRepository $warehouseRepository,
    ) {}

    public function execute(Delivery $delivery, DeliveryInput $input): DeliveryResponse
    {
        if ($delivery->getStatus() !== InquiryStatus::Draft) {
            throw InvalidStatusException::create('Delivery', 'updated', InquiryStatus::Draft->value);
        }
        $contractor = $this->contractorRepository->find($input->contractorId) ?? throw new NotFoundHttpException('Contractor not found.');
        $warehouse = $this->warehouseRepository->find($input->warehouseId) ?? throw new NotFoundHttpException('Warehouse not found.');

        $delivery->update($input, $contractor, $warehouse);

        $this->entityManager->flush();

        return DeliveryResponseMapper::map($delivery);
    }
}

<?php

namespace App\Service\Delivery;

use App\DTO\Delivery\DeliveryInput;
use App\Entity\Delivery;
use App\Repository\ContractorRepository;
use App\Repository\WarehouseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class CreateDeliveryService
{
    public function __construct(
        private readonly ContractorRepository $contractorRepository,
        private readonly WarehouseRepository $warehouseRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly Security $security
    ) {}

    public function execute(DeliveryInput $input): int
    {
        $contractor = $this->contractorRepository->find($input->contractorId) ?? throw new NotFoundHttpException('Contractor not found.');
        $warehouse = $this->warehouseRepository->find($input->warehouseId) ?? throw new NotFoundHttpException('Warehouse not found.');
        $user = $this->security->getUser() ?? throw new \LogicException('User not found.');

        $delivery = Delivery::create($input, $user, $contractor, $warehouse);

        $this->entityManager->persist($delivery);
        $this->entityManager->flush();

        return $delivery->getId();
    }
}

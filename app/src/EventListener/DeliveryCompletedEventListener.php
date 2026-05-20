<?php

namespace App\EventListener;

use App\Entity\WarehouseProduct;
use App\Enum\DeliveryProductStatus;
use App\Event\DeliveryCompletedEvent;
use App\Repository\WarehouseProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
final class DeliveryCompletedEventListener
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly WarehouseProductRepository $warehouseProductRepository,
    ) {}

    public function __invoke(DeliveryCompletedEvent $event): void
    {
        $delivery = $event->getDelivery();
        $deliveryProducts = $delivery->getDeliveryProducts();
        $warehouse = $delivery->getWarehouse();

        foreach ($deliveryProducts as $deliveryProduct) {
            if ($deliveryProduct->getStatus() === DeliveryProductStatus::Cancelled) {
                continue;
            }

            $deliveryProduct->receive();

            $product = $deliveryProduct->getProduct();
            $quantity = $deliveryProduct->getQuantity();

            $warehouseProduct = $this->warehouseProductRepository->findOneBy([
                'warehouse' => $warehouse,
                'product' => $product
            ]);

            if ($warehouseProduct) {
                $warehouseProduct->addQuantity($quantity);
            } else {
                $warehouseProduct = WarehouseProduct::create(
                    $warehouse,
                    $product,
                    $quantity,
                );
            }

            $this->entityManager->persist($warehouseProduct);
        }
    }
}
<?php

namespace App\EventListener;

use App\Enum\PurchaseOrderProductStatus;
use App\Event\PurchaseOrderCompletedEvent;
use App\Repository\WarehouseProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
final class PurchaseOrderCompletedEventListener
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly WarehouseProductRepository $warehouseProductRepository,
    ) {}

    public function __invoke(PurchaseOrderCompletedEvent $event): void
    {
        $purchaseOrder = $event->getPurchaseOrder();
        $purchaseOrderProducts = $purchaseOrder->getPurchaseOrderProducts();
        $warehouse = $purchaseOrder->getWarehouse();

        foreach ($purchaseOrderProducts as $purchaseOrderProduct) {
            if ($purchaseOrderProduct->getStatus() === PurchaseOrderProductStatus::Cancelled) {
                continue;
            }

            $purchaseOrderProduct->send();

            $product = $purchaseOrderProduct->getProduct();
            $quantity = $purchaseOrderProduct->getQuantity();

            $warehouseProduct = $this->warehouseProductRepository->findOneBy([
                'warehouse' => $warehouse,
                'product' => $product
            ]);

            $warehouseProduct->removeQuantity($quantity);

            $this->entityManager->persist($warehouseProduct);
        }
    }
}
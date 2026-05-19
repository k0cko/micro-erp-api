<?php

namespace App\Service\PurchaseOrder;

use App\DTO\PurchaseOrder\PurchaseOrderProductResponse;
use App\Entity\PurchaseOrder;
use App\Mapper\PurchaseOrder\PurchaseOrderProductResponseMapper;
use App\Repository\PurchaseOrderProductRepository;

final class ListPurchaseOrderProductService
{
    public function __construct(
        private readonly PurchaseOrderProductRepository $purchaseOrderProductRepository
    ) {}

    /** @return PurchaseOrderProductResponse[] */
    public function execute(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrderProducts = [];
        foreach ($this->purchaseOrderProductRepository->findBy(['purchaseOrder' => $purchaseOrder]) as $purchaseOrderProduct) {
            $purchaseOrderProducts[] = PurchaseOrderProductResponseMapper::map($purchaseOrderProduct);
        }

        return $purchaseOrderProducts;
    }
}

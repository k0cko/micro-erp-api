<?php

namespace App\Service\PurchaseOrder;

use App\DTO\PurchaseOrder\PurchaseOrderProductsInput;
use App\Entity\PurchaseOrder;
use App\Mapper\PurchaseOrder\PurchaseOrderProductResponseMapper;
use App\DTO\PurchaseOrder\PurchaseOrderProductResponse;
use App\Entity\PurchaseOrderProduct;
use App\Repository\ProductRepository;
use App\Repository\PurchaseOrderProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class UpdatePurchaseOrderProductService
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly PurchaseOrderProductRepository $purchaseOrderProductRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {}

    /** @return PurchaseOrderProductResponse[] */
    public function execute(PurchaseOrderProductsInput $input, PurchaseOrder $purchaseOrder): array
    {
        $purchaseOrderProducts = [];
        $productIds = [];
        foreach ($input->purchaseOrderProducts as $purchaseOrderProductInput) {
            $product = $this->productRepository->find($purchaseOrderProductInput->productId) ?? throw new NotFoundHttpException('Product not found.');
            $productIds[] = $purchaseOrderProductInput->productId;
            
            $purchaseOrderProduct = $this->purchaseOrderProductRepository->findOneBy([
                'purchaseOrder' => $purchaseOrder,
                'product' => $product
            ]);

            if (!$purchaseOrderProduct) {
                $purchaseOrderProduct = PurchaseOrderProduct::create($purchaseOrder, $product, $purchaseOrderProductInput->quantity);
                $this->entityManager->persist($purchaseOrderProduct);
            } else {
                $purchaseOrderProduct->update($purchaseOrderProductInput->quantity);
            }

            $purchaseOrderProducts[] = PurchaseOrderProductResponseMapper::map($purchaseOrderProduct);
        }

        $this->purchaseOrderProductRepository->deleteRemoved($purchaseOrder, $productIds);

        $this->entityManager->flush();

        return $purchaseOrderProducts;
    }
}

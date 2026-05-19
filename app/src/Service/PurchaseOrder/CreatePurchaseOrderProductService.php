<?php

namespace App\Service\PurchaseOrder;

use App\DTO\PurchaseOrder\PurchaseOrderProductsInput;
use App\Entity\PurchaseOrder;
use App\Entity\PurchaseOrderProduct;
use App\Enum\InquiryStatus;
use App\Exception\DuplicateResourceException;
use App\Exception\InvalidStatusException;
use App\Repository\ProductRepository;
use App\Repository\PurchaseOrderProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class CreatePurchaseOrderProductService
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly PurchaseOrderProductRepository $purchaseOrderProductRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function execute(PurchaseOrderProductsInput $input, PurchaseOrder $purchaseOrder): array
    {
        if (in_array($purchaseOrder->getStatus(), [InquiryStatus::Completed, InquiryStatus::Cancelled])) {
            throw InvalidStatusException::forInvalidAction('Purchase order products', 'created', 'purchase order', [InquiryStatus::Completed->label(), InquiryStatus::Cancelled->label()]);
        }

        $purchaseOrderProducts = [];
        foreach ($input->purchaseOrderProducts as $purchaseOrderProductInput) {
            $product = $this->productRepository->find($purchaseOrderProductInput->productId) ?? throw new NotFoundHttpException('Product not found.');

            $purchaseOrderProductExists = $this->purchaseOrderProductRepository->findOneBy([
                'purchaseOrder' => $purchaseOrder,
                'product' => $purchaseOrderProductInput->productId
            ]);
            
            if ($purchaseOrderProductExists !== null) {
                throw DuplicateResourceException::forDuplicateProduct($product->getName(), 'purchase order');
            }

            $purchaseOrderProduct = PurchaseOrderProduct::create($purchaseOrder, $product, $purchaseOrderProductInput->quantity);

            $this->entityManager->persist($purchaseOrderProduct);

            $purchaseOrderProducts[] = $purchaseOrderProduct;
        }

        $this->entityManager->flush();

        return array_map(fn($pop) => $pop->getId(), $purchaseOrderProducts);
    }
}

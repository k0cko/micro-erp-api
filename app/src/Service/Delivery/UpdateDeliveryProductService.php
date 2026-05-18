<?php

namespace App\Service\Delivery;

use App\DTO\Delivery\DeliveryProductsInput;
use App\Entity\Delivery;
use App\Enum\InquiryStatus;
use App\Exception\InvalidStatusException;
use App\Repository\DeliveryProductRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class UpdateDeliveryProductService
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly DeliveryProductRepository $deliveryProductRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function execute(DeliveryProductsInput $input, Delivery $delivery): array
    {
        if (in_array($delivery->getStatus(), [InquiryStatus::Completed, InquiryStatus::Cancelled])) {
            throw InvalidStatusException::forInvalidAction('Delivery products', 'updated', 'delivery', [InquiryStatus::Completed->label(), InquiryStatus::Cancelled->label()]);
        }

        $deliveryProducts = [];
        foreach ($input->deliveryProducts as $deliveryProductInput) {
            $product = $this->productRepository->find($deliveryProductInput->productId) ?? throw new NotFoundHttpException('Product not found.');
            $deliveryProduct = $this->deliveryProductRepository->findOneBy([
                'delivery' => $delivery,
                'product' => $product
            ]);

            if (!$deliveryProduct) { // Safe guard check
                throw new NotFoundHttpException('Delivery product with this product not found.');
            }

            $deliveryProduct->update($deliveryProductInput->quantity);

            $deliveryProducts[] = $deliveryProduct->getId();
        }

        $this->entityManager->flush();

        return $deliveryProducts;
    }
}

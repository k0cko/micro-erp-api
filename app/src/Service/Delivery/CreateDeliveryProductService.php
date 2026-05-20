<?php

namespace App\Service\Delivery;

use App\DTO\Delivery\DeliveryProductsInput;
use App\Entity\Delivery;
use App\Entity\DeliveryProduct;
use App\Exception\DuplicateResourceException;
use App\Repository\DeliveryProductRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class CreateDeliveryProductService
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly DeliveryProductRepository $deliveryProductRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function execute(DeliveryProductsInput $input, Delivery $delivery): array
    {
        $deliveryProducts = [];
        foreach ($input->deliveryProducts as $deliveryProductInput) {
            $product = $this->productRepository->find($deliveryProductInput->productId) ?? throw new NotFoundHttpException('Product not found.');

            $deliveryProductExists = $this->deliveryProductRepository->findOneBy([
                'delivery' => $delivery,
                'product' => $deliveryProductInput->productId
            ]);
            
            if ($deliveryProductExists !== null) {
                throw DuplicateResourceException::forDuplicateProduct($product->getName(), 'delivery');
            }

            $deliveryProduct = DeliveryProduct::create($delivery, $product, $deliveryProductInput->quantity);

            $this->entityManager->persist($deliveryProduct);

            $deliveryProducts[] = $deliveryProduct;
        }

        $this->entityManager->flush();

        return array_map(fn($dp) => $dp->getId(), $deliveryProducts);
    }
}

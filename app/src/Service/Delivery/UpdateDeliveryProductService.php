<?php

namespace App\Service\Delivery;

use App\DTO\Delivery\DeliveryProductsInput;
use App\Entity\Delivery;
use App\Mapper\Delivery\DeliveryProductResponseMapper;
use App\DTO\Delivery\DeliveryProductResponse;
use App\Entity\DeliveryProduct;
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

    /** @return DeliveryProductResponse[] */
    public function execute(DeliveryProductsInput $input, Delivery $delivery): array
    {
        $deliveryProducts = [];
        $productIds = [];
        foreach ($input->deliveryProducts as $deliveryProductInput) {
            $product = $this->productRepository->find($deliveryProductInput->productId) ?? throw new NotFoundHttpException('Product not found.');
            $productIds[] = $deliveryProductInput->productId;

            $deliveryProduct = $this->deliveryProductRepository->findOneBy([
                'delivery' => $delivery,
                'product' => $product
            ]);

            if (!$deliveryProduct) {
                $deliveryProduct = DeliveryProduct::create($delivery, $product, $deliveryProductInput->quantity);
                $this->entityManager->persist($deliveryProduct);
            } else {
                $deliveryProduct->update($deliveryProductInput->quantity);
            }

            $deliveryProducts[] = DeliveryProductResponseMapper::map($deliveryProduct);
        }

        $this->deliveryProductRepository->deleteRemoved($delivery, $productIds);

        $this->entityManager->flush();

        return $deliveryProducts;
    }
}

<?php

namespace App\Service\Product;

use App\DTO\Product\ProductInput;
use App\DTO\Product\ProductResponse;
use App\Entity\Product;
use App\Exception\DuplicateResourceException;
use App\Mapper\Product\ProductResponseMapper;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

final class UpdateProductService
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function execute(ProductInput $input, Product $product): ProductResponse
    {
        if ($this->productRepository->existsByName($input->name, $product->getId())) {
            throw DuplicateResourceException::forField('Product', 'name', $input->name);
        }

        $product->update($input->name, $input->description);

        $this->entityManager->flush();

        return ProductResponseMapper::map($product);
    }
}
<?php

namespace App\Service\Product;

use App\DTO\Product\ProductInput;
use App\Entity\Product;
use App\Exception\DuplicateResourceException;
use App\Mapper\Product\ProductResponseMapper;
use App\DTO\Product\ProductResponse;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

final class CreateProductService
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function execute(ProductInput $input): ProductResponse
    {
        if ($this->productRepository->existsByName($input->name)) {
            throw DuplicateResourceException::forField('Product', 'name', $input->name);
        }

        $product = Product::create($input->name, $input->description);

        $this->entityManager->persist($product);
        $this->entityManager->flush();
        
        return ProductResponseMapper::map($product);
    }

}
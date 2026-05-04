<?php

namespace App\Service\Product;

use App\DTO\Product\CreateProductInput;
use App\Entity\Product;
use App\Exception\DuplicateResourceException;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

final class CreateProductService
{
    public function __construct(
        private ProductRepository $productRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function execute(CreateProductInput $input): int
    {
        if ($this->productRepository->existsByName($input->name)) {
            throw DuplicateResourceException::forField('Product', 'name', $input->name);
        }

        $product = Product::create($input->name, $input->description);

        $this->entityManager->persist($product);
        $this->entityManager->flush();
        
        return $product->getId();
    }

}
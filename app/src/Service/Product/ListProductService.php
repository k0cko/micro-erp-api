<?php

namespace App\Service\Product;

use App\Mapper\Product\ProductResponseMapper;
use App\Repository\ProductRepository;
use App\DTO\Product\ProductResponse;

final class ListProductService
{
    public function __construct(
        private readonly ProductRepository $productRepository,
    ) {}

    /** @return ProductResponse[] */
    public function execute(): array
    {
        $productResponses = [];
        foreach ($this->productRepository->findAll() as $product) {
            $productResponses[] = ProductResponseMapper::mapToResponse($product);
        }

        return $productResponses;
    }

}
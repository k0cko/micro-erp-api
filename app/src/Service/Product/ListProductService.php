<?php

namespace App\Service\Product;

use App\DTO\Pagination\PaginatedResult;
use App\Mapper\Product\ProductResponseMapper;
use App\Repository\ProductRepository;
use App\Entity\Product;
use App\Service\Pagination\PagePaginatorService;

final class ListProductService
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly PagePaginatorService $paginator,
    ) {}

    public function execute(int $page, int $limit): PaginatedResult
    {
        return $this->paginator->paginate(
            $this->productRepository->createPaginatedQueryBuilder(),
            fn(Product $product) => ProductResponseMapper::map($product),
            $page,
            $limit,
        );
    }
}
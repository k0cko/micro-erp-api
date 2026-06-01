<?php

namespace App\Controller;

use App\DTO\Pagination\PaginationQuery;
use App\DTO\Product\ProductInput;
use App\Entity\Product;
use App\Service\Product\CreateProductService;
use App\Service\Product\DeleteProductService;
use App\Service\Product\ListProductService;
use App\Service\Product\UpdateProductService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/products')]
#[IsGranted('ROLE_ADMIN')]
final class ProductController extends AbstractApiController
{
    public function __construct(
        private readonly CreateProductService $createProductService,
        private readonly UpdateProductService $updateProductService,
        private readonly DeleteProductService $deleteProductService,
        private readonly ListProductService $listProductService,
    ) {}

    #[Route('', methods: ['GET'])]
    #[IsGranted('ROLE_WORKER')]
    public function index(
        #[MapQueryString] PaginationQuery $query,
    ): JsonResponse {
        $result = $this->listProductService->execute($query->page, $query->limit);
        return $this->jsonPaginated($result);
    }

    #[Route('', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] ProductInput $input,
    ): JsonResponse
    {
        $productResponse = $this->createProductService->execute($input);
        return $this->json($productResponse, JsonResponse::HTTP_CREATED);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(
        Product $product,
        #[MapRequestPayload] ProductInput $input,
    ): JsonResponse
    {
        $productResponse = $this->updateProductService->execute($input, $product);
        return $this->json($productResponse, JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(
        Product $product
    ): JsonResponse
    {
        $this->deleteProductService->execute($product);
        return $this->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}

<?php

namespace App\Controller;

use App\DTO\Product\ProductInput;
use App\Entity\Product;
use App\Exception\DuplicateResourceException;
use App\Exception\ResourceInUseException;
use App\Service\Product\CreateProductService;
use App\Service\Product\DeleteProductService;
use App\Service\Product\ListProductService;
use App\Service\Product\UpdateProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/products')]
#[IsGranted('ROLE_ADMIN')]
final class ProductController extends AbstractController
{
    public function __construct(
        private readonly CreateProductService $createProductService,
        private readonly UpdateProductService $updateProductService,
        private readonly DeleteProductService $deleteProductService,
        private readonly ListProductService $listProductService,
    ) {}

    #[Route('', methods: ['GET'])]
    #[IsGranted('ROLE_WORKER')]
    public function index(): JsonResponse
    {
        return $this->json($this->listProductService->execute(), JsonResponse::HTTP_OK);
    }

    #[Route('', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] ProductInput $input,
    ): JsonResponse
    {
        try {
            $id = $this->createProductService->execute($input);
        } catch (DuplicateResourceException $e) {
            return $this->json(['error' => $e->getMessage()], JsonResponse::HTTP_CONFLICT);
        }

        return $this->json(['id' => $id], JsonResponse::HTTP_CREATED);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(
        Product $product,
        #[MapRequestPayload] ProductInput $input,
    ): JsonResponse
    {
        try {
            $productResponse = $this->updateProductService->execute($input, $product);
        } catch (DuplicateResourceException $e) {
            return $this->json(['error' => $e->getMessage()], JsonResponse::HTTP_CONFLICT);
        }

        return $this->json($productResponse, JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(
        Product $product
    ): JsonResponse
    {
        try {
            $this->deleteProductService->execute($product);
        } catch (ResourceInUseException $e) {
            return $this->json(['error' => $e->getMessage()], JsonResponse::HTTP_CONFLICT);
        }

        return $this->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}

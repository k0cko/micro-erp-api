<?php

namespace App\Controller;

use App\DTO\Product\CreateProductInput;
use App\Exception\DuplicateResourceException;
use App\Service\Product\CreateProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/products')]
final class ProductController extends AbstractController
{
    public function __construct(
        private readonly CreateProductService $createProductService
    ) {}

    #[Route('', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] CreateProductInput $input,
    ): JsonResponse
    {
        try {
            $id = $this->createProductService->execute($input);
        } catch (DuplicateResourceException $e) {
            return $this->json(['error' => $e->getMessage()], JsonResponse::HTTP_CONFLICT);
        }

        return $this->json(['id' => $id], JsonResponse::HTTP_CREATED);
    }
}

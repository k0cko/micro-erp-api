<?php

namespace App\Controller;

use App\Entity\Warehouse;
use App\Service\ProductMovement\ListProductMovementService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/warehouses/{id}')]
#[IsGranted('ROLE_WORKER')]
class ProductMovementController extends AbstractController
{
    public function __construct(
        private readonly ListProductMovementService $listProductMovementService,
    ) {}

    #[Route('/product_movements', methods: ['GET'])]
    public function index(
        Warehouse $warehouse,
    ): JsonResponse {
        return $this->json($this->listProductMovementService->execute($warehouse), JsonResponse::HTTP_OK);
    }
}
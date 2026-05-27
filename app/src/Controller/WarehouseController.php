<?php

namespace App\Controller;

use App\DTO\Warehouse\WarehouseInput;
use App\Entity\Warehouse;
use App\Service\Warehouse\CreateWarehouseService;
use App\Service\Warehouse\ListWarehouseService;
use App\Service\Warehouse\UpdateWarehouseService;
use App\Service\Warehouse\DeleteWarehouseService;
use App\Service\Warehouse\ListWarehouseStockService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/warehouses')]
#[IsGranted('ROLE_WORKER')]
final class WarehouseController extends AbstractController
{
    public function __construct(
        private readonly ListWarehouseService $listWarehouseService,
        private readonly CreateWarehouseService $createWarehouseService,
        private readonly UpdateWarehouseService $updateWarehouseService,
        private readonly DeleteWarehouseService $deleteWarehouseService,
        private readonly ListWarehouseStockService $listWarehouseStockService,
    ) {}

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json($this->listWarehouseService->execute(), JsonResponse::HTTP_OK);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] WarehouseInput $input,
    ): JsonResponse
    {
        $id = $this->createWarehouseService->execute($input);
        return $this->json(['id' => $id], JsonResponse::HTTP_CREATED);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}', methods: ['PUT'])]
    public function update(
        #[MapRequestPayload] WarehouseInput $input,
        Warehouse $warehouse,
    ): JsonResponse
    {
        $warehouseResponse = $this->updateWarehouseService->execute($input, $warehouse);
        return $this->json($warehouseResponse, JsonResponse::HTTP_OK);
    }

    #[Route('/{id}/products', methods: ['GET'])]
    public function products(
        Warehouse $warehouse
    ): JsonResponse {
        $warehouseProductResponses = $this->listWarehouseStockService->execute($warehouse);

        return $this->json($warehouseProductResponses, JsonResponse::HTTP_OK);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(
        Warehouse $warehouse
    ): JsonResponse
    {
        $this->deleteWarehouseService->execute($warehouse);
        return $this->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
<?php

namespace App\Controller;

use App\DTO\Warehouse\WarehouseInput;
use App\Entity\Warehouse;
use App\Exception\DuplicateResourceException;
use App\Service\Warehouse\CreateWarehouseService;
use App\Service\Warehouse\ListWarehouseService;
use App\Service\Warehouse\UpdateWarehouseService;
use App\Service\Warehouse\DeleteWarehouseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/warehouses')]
#[IsGranted('ROLE_ADMIN')]
final class WarehouseController extends AbstractController
{
    public function __construct(
        private readonly ListWarehouseService $listWarehouseService,
        private readonly CreateWarehouseService $createWarehouseService,
        private readonly UpdateWarehouseService $updateWarehouseService,
        private readonly DeleteWarehouseService $deleteWarehouseService,
    ) {}

    #[IsGranted('ROLE_WORKER')]
    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json($this->listWarehouseService->execute(), JsonResponse::HTTP_OK);
    }

    #[Route('', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] WarehouseInput $input,
    ): JsonResponse
    {
        try {
            $id = $this->createWarehouseService->execute($input);
        } catch (DuplicateResourceException $e) {
            return $this->json(['error' => $e->getMessage()], JsonResponse::HTTP_CONFLICT);
        }

        return $this->json(['id' => $id], JsonResponse::HTTP_CREATED);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(
        #[MapRequestPayload] WarehouseInput $input,
        Warehouse $warehouse,
    ): JsonResponse
    {
        try {
            $warehouseProductResponse = $this->updateWarehouseService->execute($input, $warehouse);
        } catch (DuplicateResourceException $e) {
            return $this->json(['error' => $e->getMessage()], JsonResponse::HTTP_CONFLICT);
        }

        return $this->json($warehouseProductResponse, JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(
        Warehouse $warehouse
    ): JsonResponse
    {
        $this->deleteWarehouseService->execute($warehouse);

        return $this->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
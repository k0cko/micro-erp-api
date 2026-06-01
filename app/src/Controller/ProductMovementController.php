<?php

namespace App\Controller;

use App\DTO\Pagination\PaginationQuery;
use App\Entity\Warehouse;
use App\Service\ProductMovement\ListProductMovementService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/warehouses/{id}')]
#[IsGranted('ROLE_WORKER')]
class ProductMovementController extends AbstractApiController
{
    public function __construct(
        private readonly ListProductMovementService $listProductMovementService,
    ) {}

    #[Route('/product_movements', methods: ['GET'])]
    public function index(
        Warehouse $warehouse,
        #[MapQueryString] PaginationQuery $query,
    ): JsonResponse {
        $result = $this->listProductMovementService->execute($warehouse, $query->page, $query->limit);
        return $this->jsonPaginated($result);
    }
}
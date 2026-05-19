<?php

namespace App\Controller;

use App\DTO\PurchaseOrder\PurchaseOrderProductsInput;
use App\Entity\PurchaseOrder;
use App\Entity\PurchaseOrderProduct;
use App\Service\PurchaseOrder\CreatePurchaseOrderProductService;
use App\Service\PurchaseOrder\DeletePurchaseOrderProductService;
use App\Service\PurchaseOrder\ListPurchaseOrderProductService;
use App\Service\PurchaseOrder\UpdatePurchaseOrderProductService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/purchase_orders/{id}/products')]
#[IsGranted('ROLE_ADMIN')]
final class PurchaseOrderProductController extends AbstractController
{
    public function __construct(
        private readonly ListPurchaseOrderProductService $listPurchaseOrderProductService,
        private readonly CreatePurchaseOrderProductService $createPurchaseOrderProductService,
        private readonly UpdatePurchaseOrderProductService $updatePurchaseOrderProductService,
        private readonly DeletePurchaseOrderProductService $deletePurchaseOrderProductService,
    ) {}

    #[Route('', methods: ['GET'])]
    public function index(PurchaseOrder $purchaseOrder): JsonResponse
    {
        return $this->json($this->listPurchaseOrderProductService->execute($purchaseOrder), JsonResponse::HTTP_OK);
    }

    #[Route('', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] PurchaseOrderProductsInput $input,
        PurchaseOrder $purchaseOrder
    ): JsonResponse {
        $ids = $this->createPurchaseOrderProductService->execute($input, $purchaseOrder);
        return $this->json($ids, JsonResponse::HTTP_CREATED);
    }

    #[Route('', methods: ['PUT'])]
    public function update(
        #[MapRequestPayload] PurchaseOrderProductsInput $input,
        PurchaseOrder $purchaseOrder
    ): JsonResponse {
        $ids = $this->updatePurchaseOrderProductService->execute($input, $purchaseOrder);
        return $this->json($ids, JsonResponse::HTTP_CREATED);
    }

    #[Route('/{purchaseOrderProductId}', methods: ['DELETE'])]
    public function delete(
        PurchaseOrder $purchaseOrder,
        #[MapEntity(id: 'purchaseOrderProductId')]
        PurchaseOrderProduct $purchaseOrderProduct
    ): JsonResponse {
        $this->deletePurchaseOrderProductService->execute($purchaseOrder, $purchaseOrderProduct);
        return $this->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
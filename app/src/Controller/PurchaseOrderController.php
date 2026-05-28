<?php

namespace App\Controller;

use App\DTO\PurchaseOrder\PurchaseOrderInput;
use App\Entity\PurchaseOrder;
use App\Entity\User;
use App\Service\PurchaseOrder\CancelPurchaseOrderService;
use App\Service\PurchaseOrder\CompletePurchaseOrderService;
use App\Service\PurchaseOrder\CreatePurchaseOrderService;
use App\Service\PurchaseOrder\DeletePurchaseOrderService;
use App\Service\PurchaseOrder\ListPurchaseOrderService;
use App\Service\PurchaseOrder\StartPurchaseOrderService;
use App\Service\PurchaseOrder\UpdatePurchaseOrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/purchase_orders')]
#[IsGranted('ROLE_ADMIN')]
final class PurchaseOrderController extends AbstractController
{
    public function __construct(
        private readonly ListPurchaseOrderService $listPurchaseOrderService,
        private readonly CreatePurchaseOrderService $createPurchaseOrderService,
        private readonly UpdatePurchaseOrderService $updatePurchaseOrderService,
        private readonly DeletePurchaseOrderService $deletePurchaseOrderService,
        private readonly CompletePurchaseOrderService $completePurchaseOrderService,
        private readonly StartPurchaseOrderService $startPurchaseOrderService,
        private readonly CancelPurchaseOrderService $cancelPurchaseOrderService,
    ) {}

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json($this->listPurchaseOrderService->execute(), JsonResponse::HTTP_OK);
    }

    #[Route('', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] PurchaseOrderInput $input,
        #[CurrentUser] User $user
    ): JsonResponse {
        $purchaseOrderResponse = $this->createPurchaseOrderService->execute($input, $user);
        return $this->json($purchaseOrderResponse, JsonResponse::HTTP_CREATED);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(
        PurchaseOrder $purchaseOrder,
        #[MapRequestPayload] PurchaseOrderInput $input
    ): JsonResponse {
        $purchaseOrderResponse = $this->updatePurchaseOrderService->execute($purchaseOrder, $input);
        return $this->json($purchaseOrderResponse, JsonResponse::HTTP_OK);
    }

    #[Route('/{id}/start', methods: ['PUT'])]
    public function start(
        PurchaseOrder $purchaseOrder,
    ): JsonResponse {
        $purchaseOrderResponse = $this->startPurchaseOrderService->execute($purchaseOrder);
        return $this->json($purchaseOrderResponse, JsonResponse::HTTP_OK);
    }

    #[Route('/{id}/cancel', methods: ['PUT'])]
    public function cancel(
        PurchaseOrder $purchaseOrder,
    ): JsonResponse {
        $purchaseOrderResponse = $this->cancelPurchaseOrderService->execute($purchaseOrder);
        return $this->json($purchaseOrderResponse, JsonResponse::HTTP_OK);
    }

    #[Route('/{id}/complete', methods: ['PUT'])]
    public function complete(
        PurchaseOrder $purchaseOrder
    ): JsonResponse {
        $purchaseOrderResponse = $this->completePurchaseOrderService->execute($purchaseOrder);
        return $this->json($purchaseOrderResponse, JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(
        PurchaseOrder $purchaseOrder
    ): JsonResponse {
        $this->deletePurchaseOrderService->execute($purchaseOrder);
        return $this->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
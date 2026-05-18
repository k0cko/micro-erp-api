<?php

namespace App\Controller;

use App\DTO\Delivery\DeliveryInput;
use App\Entity\Delivery;
use App\Entity\User;
use App\Service\Delivery\CreateDeliveryService;
use App\Service\Delivery\DeleteDeliveryService;
use App\Service\Delivery\ListDeliveryService;
use App\Service\Delivery\UpdateDeliveryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/deliveries')]
#[IsGranted('ROLE_ADMIN')]
final class DeliveryController extends AbstractController
{
    public function __construct(
        private readonly ListDeliveryService $listDeliveryService,
        private readonly CreateDeliveryService $createDeliveryService,
        private readonly UpdateDeliveryService $updateDeliveryService,
        private readonly DeleteDeliveryService $deleteDeliveryService,
    ) {}

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json($this->listDeliveryService->execute(), JsonResponse::HTTP_OK);
    }

    #[Route('', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] DeliveryInput $input,
        #[CurrentUser] User $user
    ): JsonResponse {
        $id = $this->createDeliveryService->execute($input, $user);
        return $this->json(['id' => $id], JsonResponse::HTTP_CREATED);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(
        Delivery $delivery,
        #[MapRequestPayload] DeliveryInput $input
    ): JsonResponse {
        $deliveryResponse = $this->updateDeliveryService->execute($delivery, $input);
        return $this->json($deliveryResponse, JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(
        Delivery $delivery
    ): JsonResponse {
        $this->deleteDeliveryService->execute($delivery);
        return $this->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}

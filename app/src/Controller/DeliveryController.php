<?php

namespace App\Controller;

use App\DTO\Delivery\DeliveryInput;
use App\Entity\Delivery;
use App\Exception\InvalidStatusException;
use App\Service\Delivery\CreateDeliveryService;
use App\Service\Delivery\DeleteDeliveryService;
use App\Service\Delivery\ListDeliveryService;
use App\Service\Delivery\UpdateDeliveryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/deliveries')]
#[IsGranted('ROLE_ADMIN')]
class DeliveryController extends AbstractController
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
        #[MapRequestPayload] DeliveryInput $input
    ): JsonResponse {
        $id = $this->createDeliveryService->execute($input);

        return $this->json(['id' => $id], JsonResponse::HTTP_CREATED);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(
        Delivery $delivery,
        #[MapRequestPayload] DeliveryInput $input
    ): JsonResponse {
        try {
            $deliveryResponse = $this->updateDeliveryService->execute($delivery, $input);
        } catch (InvalidStatusException $e) {
            return $this->json(['error' => $e->getMessage()], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->json($deliveryResponse, JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(
        Delivery $delivery
    ): JsonResponse {
        try {
            $this->deleteDeliveryService->execute($delivery);
        } catch (InvalidStatusException $e) {
            return $this->json(['error' => $e->getMessage()], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        return $this->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}

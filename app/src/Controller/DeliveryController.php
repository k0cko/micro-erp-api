<?php

namespace App\Controller;

use App\DTO\Delivery\DeliveryInput;
use App\DTO\Pagination\PaginationQuery;
use App\Entity\Delivery;
use App\Entity\User;
use App\Service\Delivery\CompleteDeliveryService;
use App\Service\Delivery\CreateDeliveryService;
use App\Service\Delivery\DeleteDeliveryService;
use App\Service\Delivery\ListDeliveryService;
use App\Service\Delivery\UpdateDeliveryService;
use App\Service\Delivery\StartDeliveryService;
use App\Service\Delivery\CancelDeliveryService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/deliveries')]
#[IsGranted('ROLE_ADMIN')]
final class DeliveryController extends AbstractApiController
{
    public function __construct(
        private readonly ListDeliveryService $listDeliveryService,
        private readonly CreateDeliveryService $createDeliveryService,
        private readonly UpdateDeliveryService $updateDeliveryService,
        private readonly DeleteDeliveryService $deleteDeliveryService,
        private readonly CompleteDeliveryService $completeDeliveryService,
        private readonly StartDeliveryService $startDeliveryService,
        private readonly CancelDeliveryService $cancelDeliveryService,
    ) {}

    #[Route('', methods: ['GET'])]
    #[IsGranted('ROLE_WORKER')]
    public function index(
        #[MapQueryString] PaginationQuery $query,
    ): JsonResponse {
        $result = $this->listDeliveryService->execute($query->page, $query->limit);
        return $this->jsonPaginated($result);
    }

    #[Route('', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] DeliveryInput $input,
        #[CurrentUser] User $user
    ): JsonResponse {
        $deliveryResponse = $this->createDeliveryService->execute($input, $user);
        return $this->json($deliveryResponse, JsonResponse::HTTP_CREATED);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(
        Delivery $delivery,
        #[MapRequestPayload] DeliveryInput $input
    ): JsonResponse {
        $deliveryResponse = $this->updateDeliveryService->execute($delivery, $input);
        return $this->json($deliveryResponse, JsonResponse::HTTP_OK);
    }

    #[Route('/{id}/start', methods: ['PUT'])]
    public function start(
        Delivery $delivery
    ): JsonResponse {
        $deliveryResponse = $this->startDeliveryService->execute($delivery);
        return $this->json($deliveryResponse, JsonResponse::HTTP_OK);
    }

    #[Route('/{id}/cancel', methods: ['PUT'])]
    public function cancel(
        Delivery $delivery
    ): JsonResponse {
        $deliveryResponse = $this->cancelDeliveryService->execute($delivery);
        return $this->json($deliveryResponse, JsonResponse::HTTP_OK);
    }    

    #[Route('/{id}/complete', methods: ['PUT'])]
    public function complete(
        Delivery $delivery
    ): JsonResponse {
        $deliveryResponse = $this->completeDeliveryService->execute($delivery);
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

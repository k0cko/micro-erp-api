<?php

namespace App\Controller;

use App\DTO\Delivery\DeliveryProductsInput;
use App\Entity\Delivery;
use App\Entity\DeliveryProduct;
use App\Service\Delivery\CreateDeliveryProductService;
use App\Service\Delivery\ListDeliveryProductService;
use App\Service\Delivery\UpdateDeliveryProductService;
use App\Service\Delivery\DeleteDeliveryProductService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/deliveries/{id}/products')]
#[IsGranted('ROLE_ADMIN')]
final class DeliveryProductController extends AbstractController
{
    public function __construct(
        private readonly ListDeliveryProductService $listDeliveryProductService,
        private readonly CreateDeliveryProductService $createDeliveryProductService,
        private readonly UpdateDeliveryProductService $updateDeliveryProductService,
        private readonly DeleteDeliveryProductService $deleteDeliveryProductService,
    ) {}

    #[Route('', methods: ['GET'])]
    public function index(Delivery $delivery): JsonResponse
    {
        return $this->json($this->listDeliveryProductService->execute($delivery), JsonResponse::HTTP_OK);
    }

    #[Route('', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] DeliveryProductsInput $input,
        Delivery $delivery
    ): JsonResponse {
        $ids = $this->createDeliveryProductService->execute($input, $delivery);
        return $this->json($ids, JsonResponse::HTTP_CREATED);
    }

    #[Route('', methods: ['PUT'])]
    public function update(
        #[MapRequestPayload] DeliveryProductsInput $input,
        Delivery $delivery
    ): JsonResponse {
        $ids = $this->updateDeliveryProductService->execute($input, $delivery);
        return $this->json($ids, JsonResponse::HTTP_CREATED);
    }

    #[Route('/{deliveryProductId}', methods: ['DELETE'])]
    public function delete(
        Delivery $delivery,
        #[MapEntity(id: 'deliveryProductId')]
        DeliveryProduct $deliveryProduct
    ): JsonResponse {
        $this->deleteDeliveryProductService->execute($delivery, $deliveryProduct);
        return $this->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
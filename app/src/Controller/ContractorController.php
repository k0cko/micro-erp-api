<?php

namespace App\Controller;

use App\DTO\Contractor\ContractorInput;
use App\Entity\Contractor;
use App\Exception\DuplicateResourceException;
use App\Exception\ResourceInUseException;
use App\Service\Contractor\CreateContractorService;
use App\Service\Contractor\DeleteContractorService;
use App\Service\Contractor\ListContractorService;
use App\Service\Contractor\UpdateContractorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/contractors')]
final class ContractorController extends AbstractController
{
    public function __construct(
        private readonly ListContractorService $listContractorService,
        private readonly CreateContractorService $createContractorService,
        private readonly UpdateContractorService $updateContractorService,
        private readonly DeleteContractorService $deleteContractorService
    ) {}

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json($this->listContractorService->execute(), JsonResponse::HTTP_OK);
    }

    #[Route('', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] ContractorInput $input,
    ): JsonResponse
    {
        try {
            $id = $this->createContractorService->execute($input);
        } catch (DuplicateResourceException $e) {
            return $this->json(['error' => $e->getMessage()], JsonResponse::HTTP_CONFLICT);
        }

        return $this->json(['id' => $id], JsonResponse::HTTP_CREATED);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(
        Contractor $contractor,
        #[MapRequestPayload] ContractorInput $input
    ): JsonResponse
    {
        try {
            $contractorResponse = $this->updateContractorService->execute($contractor, $input); 
        } catch (DuplicateResourceException $e) {
            return $this->json(['error' => $e->getMessage()], JsonResponse::HTTP_CONFLICT);
        }

        return $this->json($contractorResponse, JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(
        Contractor $contractor
    ): JsonResponse
    {
        try {
            $this->deleteContractorService->execute($contractor);
        } catch (ResourceInUseException $e) {
            return $this->json(['error' => $e->getMessage()], JsonResponse::HTTP_CONFLICT);
        }

        return $this->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
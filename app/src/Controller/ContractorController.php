<?php

namespace App\Controller;

use App\DTO\Contractor\ContractorInput;
use App\Entity\Contractor;
use App\Exception\DuplicateResourceException;
use App\Service\Contractor\CreateContractorService;
use App\Service\Contractor\DeleteContractorService;
use App\Service\Contractor\ListContractorService;
use App\Service\Contractor\UpdateContractorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/contractors')]
#[IsGranted('ROLE_ADMIN')]
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
        $id = $this->createContractorService->execute($input);
        return $this->json(['id' => $id], JsonResponse::HTTP_CREATED);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(
        Contractor $contractor,
        #[MapRequestPayload] ContractorInput $input
    ): JsonResponse
    {
        $contractorResponse = $this->updateContractorService->execute($contractor, $input);
        return $this->json($contractorResponse, JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(
        Contractor $contractor
    ): JsonResponse
    {
        $this->deleteContractorService->execute($contractor);

        return $this->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}

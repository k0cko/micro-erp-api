<?php

namespace App\Service\Contractor;

use App\DTO\Contractor\ContractorResponse;
use App\Mapper\Contractor\ContractorResponseMapper;
use App\Repository\ContractorRepository;

final class ListContractorService
{
    public function __construct(
        private readonly ContractorRepository $contractorRepository,
        private readonly ContractorResponseMapper $contractorResponseMapper,
    ) {}

    /** @return ContractorResponse[] */
    public function execute(): array
    {
        $contractorResponses = [];
        foreach ($this->contractorRepository->findAll() as $contractor) {
            $contractorResponses[] = $this->contractorResponseMapper->mapToResponse($contractor);
        }

        return $contractorResponses;
    }

}
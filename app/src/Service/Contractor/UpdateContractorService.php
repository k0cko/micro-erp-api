<?php

namespace App\Service\Contractor;

use App\DTO\Contractor\ContractorInput;
use App\DTO\Contractor\ContractorResponse;
use App\Entity\Contractor;
use App\Enum\ContractorType;
use App\Exception\DuplicateResourceException;
use App\Mapper\Contractor\ContractorResponseMapper;
use App\Repository\ContractorRepository;
use Doctrine\ORM\EntityManagerInterface;

final class UpdateContractorService
{
    public function __construct(
        private readonly ContractorRepository $contractorRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function execute(Contractor $contractor, ContractorInput $input): ContractorResponse
    {
        if ($this->contractorRepository->existsByName($input->name, $contractor->getId())) {
            throw DuplicateResourceException::forField('Contractor', 'name', $input->name);
        }

        $contractor->update($input->name, ContractorType::from($input->type));

        $this->entityManager->flush();

        return ContractorResponseMapper::mapToResponse($contractor);
    }
}
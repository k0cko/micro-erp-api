<?php

namespace App\Service\Contractor;

use App\DTO\Contractor\ContractorInput;
use App\DTO\Contractor\ContractorResponse;
use App\Entity\Contractor;
use App\Exception\DuplicateResourceException;
use App\Mapper\Contractor\ContractorResponseMapper;
use App\Repository\ContractorRepository;
use Doctrine\ORM\EntityManagerInterface;

final class CreateContractorService
{
    public function __construct(
        private readonly ContractorRepository $contractorRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function execute(ContractorInput $input): ContractorResponse
    {
        if ($this->contractorRepository->existsByName($input->name)) {
            throw DuplicateResourceException::forField('Contractor', 'name', $input->name);
        }

        $contractor = Contractor::create($input);

        $this->entityManager->persist($contractor);
        $this->entityManager->flush();

        return ContractorResponseMapper::map($contractor);
    }
}
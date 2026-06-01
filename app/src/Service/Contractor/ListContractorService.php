<?php

namespace App\Service\Contractor;

use App\DTO\Pagination\PaginatedResult;
use App\Entity\Contractor;
use App\Mapper\Contractor\ContractorResponseMapper;
use App\Repository\ContractorRepository;
use App\Service\Pagination\PagePaginatorService;

final class ListContractorService
{
    public function __construct(
        private readonly ContractorRepository $contractorRepository,
        private readonly PagePaginatorService $paginator,
    ) {}

    public function execute(int $page, int $limit): PaginatedResult
    {
        return $this->paginator->paginate(
            $this->contractorRepository->createPaginatedQueryBuilder(),
            fn(Contractor $contractor) => ContractorResponseMapper::map($contractor),
            $page,
            $limit,
        );
    }
}
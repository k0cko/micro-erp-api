<?php

namespace App\Mapper\Contractor;

use App\DTO\Contractor\ContractorResponse;
use App\Entity\Contractor;

final readonly class ContractorResponseMapper
{
    public static function mapToResponse(Contractor $contractor): ContractorResponse
    {
        return new ContractorResponse(
            id: $contractor->getId(),
            name: $contractor->getName(),
            type: $contractor->getType()->label(),
            createdAt: $contractor->getCreatedAt(),
            updatedAt: $contractor->getUpdatedAt(),
        );
    }
}
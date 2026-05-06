<?php

namespace App\DTO\Contractor;

use App\Enum\ContractorType;

final readonly class ContractorResponse
{
    public function __construct(
        public int $id,
        public string $name,
        public ContractorType $type,
        public \DateTimeImmutable $createdAt,
        public \DateTimeImmutable $updatedAt,
    ) {}
}
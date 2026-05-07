<?php

namespace App\DTO\Contractor;

use App\Enum\ContractorType;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class ContractorInput {

    public function __construct(
        #[Assert\NotBlank(message: 'Name must not be blank.')]
        #[Assert\Length(
            max: 60,
            maxMessage: 'Name cannot exceed {{ limit }} characters.'
        )]
        public readonly string $name,

        #[Assert\NotBlank(message: 'Type must not be blank.')]
        #[Assert\Choice(callback: [ContractorType::class, 'values'])]
        public readonly string $type,
    ) {}
}
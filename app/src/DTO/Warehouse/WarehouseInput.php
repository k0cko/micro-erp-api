<?php

namespace App\DTO\Warehouse;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class WarehouseInput
{
    public function __construct(
        #[Assert\NotBlank(message: 'Name must not be blank.')]
        #[Assert\Length(
            max: 60,
            maxMessage: 'Name cannot exceed {{ limit }} characters.'
        )]
        public readonly string $name
    ) {}
}
<?php

namespace App\DTO\Warehouse;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class WarehouseInput
{
    public function __construct(
        #[Assert\NotBlank(message: 'Name must not be blank.')]
        #[Assert\Length(
            max: 255,
            maxMessage: 'Name cannot exceed 255 characters.'
        )]
        public readonly string $name
    ) {}
}
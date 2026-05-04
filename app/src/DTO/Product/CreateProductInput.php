<?php

namespace App\DTO\Product;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateProductInput {

    public function __construct(
        #[Assert\NotBlank(message: 'name must not be blank.')]
        #[Assert\Length(
            max: 255,
            maxMessage: 'name cannot exceed 255 characters.'
        )]
        public readonly string $name,

        #[Assert\Length(
            max: 255,
            maxMessage: 'description cannot exceed 255 characters.'
        )]
        public readonly ?string $description = null,
    ) {}
}
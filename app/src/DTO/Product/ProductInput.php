<?php

namespace App\DTO\Product;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class ProductInput {

    public function __construct(
        #[Assert\NotBlank(message: 'Name must not be blank.')]
        #[Assert\Length(
            max: 60,
            maxMessage: 'Name cannot exceed {{ limit }} characters.'
        )]
        public readonly string $name,

        #[Assert\Length(
            max: 255,
            maxMessage: 'Description cannot exceed 255 characters.'
        )]
        public readonly ?string $description = null,
    ) {}
}
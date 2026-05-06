<?php

namespace App\DTO\User;

use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateUserInput
{
    public function __construct(
        #[Assert\NotBlank(message: 'First name must not be blank.')]
        #[Assert\Length(max: 64, maxMessage: 'First name cannot exceed 64 characters.')]
        #[SerializedName('first_name')]
        public readonly string $firstName,

        #[Assert\NotBlank(message: 'Last name must not be blank.')]
        #[Assert\Length(max: 64, maxMessage: 'Last name cannot exceed 64 characters.')]
        #[SerializedName('last_name')]
        public readonly string $lastName,
    ) {}
}
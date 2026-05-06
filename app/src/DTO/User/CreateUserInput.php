<?php

namespace App\DTO\User;

use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateUserInput
{
    public function __construct(
        #[Assert\NotBlank(message: 'Username must not be blank.')]
        #[Assert\Length(max: 180, maxMessage: 'Username cannot exceed 180 characters.')]
        public readonly string $username,
        
        #[Assert\NotBlank(message: 'Password must not be blank.')]
        public readonly string $password,

        #[Assert\EqualTo(propertyPath: 'password', message: 'Passwords do not match.')]
        #[SerializedName('confirmed_password')]
        public readonly string $confirmedPassword,

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
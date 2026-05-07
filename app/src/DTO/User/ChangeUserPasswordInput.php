<?php

namespace App\DTO\User;

use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class ChangeUserPasswordInput
{
    public function __construct(
        #[Assert\NotBlank(message: 'Old password can not be blank.')]
        #[SerializedName('old_password')]
        public readonly string $oldPassword,

        #[Assert\Length(min: 8, minMessage: 'Password must be at least {{ limit }} characters long')]
        #[Assert\NotBlank(message: 'New password must not be blank.')]
        #[SerializedName('new_password')]
        public readonly string $newPassword,

        #[Assert\EqualTo(propertyPath: 'newPassword', message: 'Passwords do not match.')]
        #[SerializedName('confirmed_password')]
        public readonly string $confirmedPassword,
    ) {}
}
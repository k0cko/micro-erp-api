<?php

namespace App\Security\Voter;

use App\DTO\User\CreateUserInput;
use App\Enum\UserRole;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserVoter extends Voter
{
    public const CREATE_SUPER_ADMIN = 'CREATE_SUPER_ADMIN';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::CREATE_SUPER_ADMIN
            && $subject instanceof CreateUserInput;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            $vote?->addReason('The user must be logged in to access this resource.');

            return false;
        }

        return match($attribute) {
            self::CREATE_SUPER_ADMIN => $this->decide($subject, $user, $vote),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    private function decide(CreateUserInput $input, UserInterface $user, ?Vote $vote): bool
    {
        if (!$this->wantsToCreateSuperAdmin($input->role)) {
            return true;
        }
        return $this->canCreateSuperAdmin($user, $vote);
    }

    private function wantsToCreateSuperAdmin(string $role): bool
    {
        return $role === UserRole::SuperAdmin->value;
    }

    private function canCreateSuperAdmin(UserInterface $user, ?Vote $vote): bool
    {
        if (in_array('ROLE_SUPER_ADMIN', $user->getRoles(), true)) {
            return true;
        }

        $vote?->addReason('Only Super Admin users can create other Super Admin users.');

        return false;
    }
}

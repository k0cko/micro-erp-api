<?php

namespace App\Service\User;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

final class DeleteUserService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function execute(User $user): void
    {
        $user->softDelete();
        $this->entityManager->flush();
    }
}
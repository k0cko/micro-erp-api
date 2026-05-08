<?php

namespace App\Service\Contractor;

use App\Entity\Contractor;
use Doctrine\ORM\EntityManagerInterface;

final class DeleteContractorService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function execute(Contractor $contractor): void
    {
        $contractor->softDelete();
        $this->entityManager->flush();
    }
}
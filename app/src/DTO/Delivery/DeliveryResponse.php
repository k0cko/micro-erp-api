<?php

namespace App\DTO\Delivery;

final readonly class DeliveryResponse
{
    public function __construct(
        public readonly string $number,
        public readonly string $status,
        public readonly string $contractor,
        public readonly string $warehouse,
        public readonly \DateTimeImmutable $date,
        public readonly \DateTimeImmutable $createdAt,
        public readonly \DateTimeImmutable $updatedAt,
    ) {}
}

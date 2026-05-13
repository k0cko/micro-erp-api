<?php

namespace App\DTO\Delivery;

use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class DeliveryInput
{
    public function __construct(
        #[Assert\NotBlank(message: 'Contractor can not be blank.')]
        #[SerializedName('contractor_id')]
        public readonly int $contractorId,
        #[Assert\NotBlank(message: 'Warehouse can not be blank.')]
        #[SerializedName('warehouse_id')]
        public readonly int $warehouseId,
        #[Assert\NotBlank(message: 'Date can not be blank.')]
        public readonly \DateTimeImmutable $date,
    ) {}
}

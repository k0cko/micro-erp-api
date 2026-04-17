<?php

namespace App\Entity;

use App\Enum\InquiryProductStatus;
use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass]
abstract class InquiryProduct
{
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column(enumType: InquiryProductStatus::class)]
    private ?InquiryProductStatus $status = null;

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getStatus(): ?InquiryProductStatus
    {
        return $this->status;
    }

    public function setStatus(InquiryProductStatus $status): static
    {
        $this->status = $status;

        return $this;
    }
}

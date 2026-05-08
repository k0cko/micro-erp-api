<?php

namespace App\Entity;

use App\Entity\Trait\TimestampableTrait;
use App\Enum\InquiryStatus;
use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass]
abstract class Inquiry
{
    use TimestampableTrait;

    #[ORM\Column]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column]
    private ?int $number = null;

    #[ORM\Column(enumType: InquiryStatus::class)]
    private ?InquiryStatus $status = null;

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getStatus(): ?InquiryStatus
    {
        return $this->status;
    }

    public function setStatus(InquiryStatus $status): static
    {
        $this->status = $status;

        return $this;
    }
}

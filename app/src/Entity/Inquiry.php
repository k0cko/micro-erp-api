<?php

namespace App\Entity;

use App\Entity\Trait\TimestampableTrait;
use App\Enum\InquiryStatus;
use App\Exception\InvalidStatusException;
use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass]
abstract class Inquiry
{
    use TimestampableTrait;

    #[ORM\Column]
    protected ?\DateTimeImmutable $date = null;

    #[ORM\Column(enumType: InquiryStatus::class)]
    protected ?InquiryStatus $status = null;

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getStatus(): InquiryStatus
    {
        return $this->status;
    }

    public function setStatus(InquiryStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    /** @param InquiryStatus[] $allowed */
    protected function isStatusAllowed(array $allowed): bool
    {
        return in_array($this->status, $allowed);
    }

    protected function assertCanModifyEntity(string $entityName, string $action, InquiryStatus ...$allowed): void
    {
        if (!$this->isStatusAllowed($allowed)) {
            throw InvalidStatusException::create($entityName, $action, array_map(fn($status) => $status->label(), $allowed));
        }
    }
}

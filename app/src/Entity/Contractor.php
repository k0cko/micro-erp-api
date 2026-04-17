<?php

namespace App\Entity;

use App\Enum\ContractorType;
use App\Repository\ContractorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContractorRepository::class)]
class Contractor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(enumType: ContractorType::class)]
    private ?ContractorType $type = null;

    /**
     * @var Collection<int, Inquiry>
     */
    #[ORM\OneToMany(targetEntity: Inquiry::class, mappedBy: 'contractor')]
    private Collection $inquiries;

    public function __construct()
    {
        $this->inquiries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?ContractorType
    {
        return $this->type;
    }

    public function setType(ContractorType $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Inquiry>
     */
    public function getInquiries(): Collection
    {
        return $this->inquiries;
    }

    public function addInquiry(Inquiry $inquiry): static
    {
        if (!$this->inquiries->contains($inquiry)) {
            $this->inquiries->add($inquiry);
            $inquiry->setContractor($this);
        }

        return $this;
    }

    public function removeInquiry(Inquiry $inquiry): static
    {
        $this->inquiries->removeElement($inquiry);

        return $this;
    }
}

<?php

namespace App\Entity;

use App\DTO\Contractor\ContractorInput;
use App\Entity\Trait\TimestampableTrait;
use App\Enum\ContractorType;
use App\Repository\ContractorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContractorRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Contractor
{
    use TimestampableTrait;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(enumType: ContractorType::class)]
    private ?ContractorType $type = null;

    /**
     * @var Collection<int, PurchaseOrder>
     */
    #[ORM\OneToMany(targetEntity: PurchaseOrder::class, mappedBy: 'contractor')]
    private Collection $purchaseOrders;

    /**
     * @var Collection<int, Delivery>
     */
    #[ORM\OneToMany(targetEntity: Delivery::class, mappedBy: 'contractor')]
    private Collection $deliveries;

    public function __construct(string $name, ContractorType $type)
    {
        $this->name = $name;
        $this->type = $type;
        $this->purchaseOrders = new ArrayCollection();
        $this->deliveries = new ArrayCollection();
    }

    public static function create(ContractorInput $input): self
    {
        return new self($input->name, ContractorType::from($input->type));
    }

    public function update(string $name, ContractorType $type): void
    {
        $this->name = $name;
        $this->type = $type;
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
     * @return Collection<int, PurchaseOrder>
     */
    public function getPurchaseOrders(): Collection
    {
        return $this->purchaseOrders;
    }

    /**
     * @return Collection<int, Delivery>
     */
    public function getDeliveries(): Collection
    {
        return $this->deliveries;
    }
}

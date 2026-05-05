<?php

namespace App\Entity;

use App\Entity\Trait\TimestampableTrait;
use App\Repository\WarehouseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WarehouseRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Warehouse
{
    use TimestampableTrait;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Inquiry>
     */
    #[ORM\OneToMany(targetEntity: Inquiry::class, mappedBy: 'warehouse')]
    private Collection $inquiries;

    /**
     * @var Collection<int, WarehouseProduct>
     */
    #[ORM\OneToMany(targetEntity: WarehouseProduct::class, mappedBy: 'warehouse')]
    private Collection $warehouseProducts;

    /**
     * @var Collection<int, ProductMovement>
     */
    #[ORM\OneToMany(targetEntity: ProductMovement::class, mappedBy: 'warehouse')]
    private Collection $productMovements;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->inquiries = new ArrayCollection();
        $this->warehouseProducts = new ArrayCollection();
        $this->productMovements = new ArrayCollection();
    }

    public static function create(string $name): self
    {
        return new self($name);
    }

    public function update(string $name): void
    {
        $this->name = $name;
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
            $inquiry->setWarehouse($this);
        }

        return $this;
    }

    public function removeInquiry(Inquiry $inquiry): static
    {
        $this->inquiries->removeElement($inquiry);

        return $this;
    }

    /**
     * @return Collection<int, WarehouseProduct>
     */
    public function getWarehouseProducts(): Collection
    {
        return $this->warehouseProducts;
    }

    public function addWarehouseProduct(WarehouseProduct $warehouseProduct): static
    {
        if (!$this->warehouseProducts->contains($warehouseProduct)) {
            $this->warehouseProducts->add($warehouseProduct);
            $warehouseProduct->setWarehouse($this);
        }

        return $this;
    }

    public function removeWarehouseProduct(WarehouseProduct $warehouseProduct): static
    {
        if ($this->warehouseProducts->removeElement($warehouseProduct)) {
            // set the owning side to null (unless already changed)
            if ($warehouseProduct->getWarehouse() === $this) {
                $warehouseProduct->setWarehouse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProductMovement>
     */
    public function getProductMovements(): Collection
    {
        return $this->productMovements;
    }

    public function addProductMovement(ProductMovement $productMovement): static
    {
        if (!$this->productMovements->contains($productMovement)) {
            $this->productMovements->add($productMovement);
            $productMovement->setWarehouse($this);
        }

        return $this;
    }

    public function removeProductMovement(ProductMovement $productMovement): static
    {
        if ($this->productMovements->removeElement($productMovement)) {
            // set the owning side to null (unless already changed)
            if ($productMovement->getWarehouse() === $this) {
                $productMovement->setWarehouse(null);
            }
        }

        return $this;
    }
}

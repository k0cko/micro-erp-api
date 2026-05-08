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
     * @var Collection<int, PurchaseOrder>
     */
    #[ORM\OneToMany(targetEntity: PurchaseOrder::class, mappedBy: 'warehouse')]
    private Collection $purchaseOrders;

    /**
     * @var Collection<int, Delivery>
     */
    #[ORM\OneToMany(targetEntity: Delivery::class, mappedBy: 'warehouse')]
    private Collection $deliveries;

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
        $this->purchaseOrders = new ArrayCollection();
        $this->deliveries = new ArrayCollection();
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

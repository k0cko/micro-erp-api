<?php

namespace App\Entity;

use App\Entity\Trait\TimestampableTrait;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Product
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    
    /** Unique case-insensitively. See migration Version20260504084413. */
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, WarehouseProduct>
     */
    #[ORM\OneToMany(targetEntity: WarehouseProduct::class, mappedBy: 'product')]
    private Collection $warehouseProducts;

    /**
     * @var Collection<int, ProductMovement>
     */
    #[ORM\OneToMany(targetEntity: ProductMovement::class, mappedBy: 'product')]
    private Collection $productMovements;

    public function __construct(string $name, ?string $description)
    {
        $this->name = $name;
        $this->description = $description;
        $this->warehouseProducts = new ArrayCollection();
        $this->productMovements = new ArrayCollection();
    }

    public static function create(string $name, ?string $description = null): self
    {
        return new self($name, $description);
    }

    public function update(string $name, ?string $description = null): void
    {
        $this->rename($name);
        $this->updateDescription($description);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function rename(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function updateDescription(?string $description): void
    {
        $this->description = $description;
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
            $warehouseProduct->setProduct($this);
        }

        return $this;
    }

    public function removeWarehouseProduct(WarehouseProduct $warehouseProduct): static
    {
        if ($this->warehouseProducts->removeElement($warehouseProduct)) {
            // set the owning side to null (unless already changed)
            if ($warehouseProduct->getProduct() === $this) {
                $warehouseProduct->setProduct(null);
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
            $productMovement->setProduct($this);
        }

        return $this;
    }

    public function removeProductMovement(ProductMovement $productMovement): static
    {
        if ($this->productMovements->removeElement($productMovement)) {
            // set the owning side to null (unless already changed)
            if ($productMovement->getProduct() === $this) {
                $productMovement->setProduct(null);
            }
        }

        return $this;
    }
}

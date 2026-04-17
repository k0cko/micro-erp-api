<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

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

    public function __construct()
    {
        $this->warehouseProducts = new ArrayCollection();
        $this->productMovements = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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

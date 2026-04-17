<?php

namespace App\Entity;

use App\Enum\ProductMovementType;
use App\Repository\ProductMovementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductMovementRepository::class)]
class ProductMovement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'productMovements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Warehouse $warehouse = null;

    #[ORM\ManyToOne(inversedBy: 'productMovements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'productMovements')]
    private ?PurchaseOrderProduct $purchase_order_product = null;

    #[ORM\ManyToOne(inversedBy: 'productMovements')]
    private ?DeliveryProduct $delivery_product = null;

    #[ORM\Column(enumType: ProductMovementType::class)]
    private ?ProductMovementType $type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWarehouse(): ?Warehouse
    {
        return $this->warehouse;
    }

    public function setWarehouse(?Warehouse $warehouse): static
    {
        $this->warehouse = $warehouse;

        return $this;
    }

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

    public function getPurchaseOrderProduct(): ?PurchaseOrderProduct
    {
        return $this->purchase_order_product;
    }

    public function setPurchaseOrderProduct(?PurchaseOrderProduct $purchase_order_product): static
    {
        $this->purchase_order_product = $purchase_order_product;

        return $this;
    }

    public function getDeliveryProduct(): ?DeliveryProduct
    {
        return $this->delivery_product;
    }

    public function setDeliveryProduct(?DeliveryProduct $delivery_product): static
    {
        $this->delivery_product = $delivery_product;

        return $this;
    }

    public function getType(): ?ProductMovementType
    {
        return $this->type;
    }

    public function setType(ProductMovementType $type): static
    {
        $this->type = $type;

        return $this;
    }
}

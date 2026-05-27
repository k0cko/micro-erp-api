<?php

namespace App\Entity;

use App\Entity\Trait\TimestampableTrait;
use App\Enum\ProductMovementType;
use App\Repository\ProductMovementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductMovementRepository::class)]
#[ORM\HasLifecycleCallbacks]
class ProductMovement
{
    use TimestampableTrait;

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
    private ?PurchaseOrderProduct $purchaseOrderProduct = null;

    #[ORM\ManyToOne(inversedBy: 'productMovements')]
    private ?DeliveryProduct $deliveryProduct = null;

    #[ORM\Column(enumType: ProductMovementType::class)]
    private ?ProductMovementType $type = null;

    public function __construct(
        Warehouse $warehouse,
        Product $product,
        int $quantity,
        ProductMovementType $type,
        ?PurchaseOrderProduct $purchaseOrderProduct,
        ?DeliveryProduct $deliveryProduct
    ) {
        $this->warehouse = $warehouse;
        $this->product = $product;
        $this->quantity = $quantity;
        $this->type = $type;
        $this->purchaseOrderProduct = $purchaseOrderProduct;
        $this->deliveryProduct = $deliveryProduct;
    }

    public static function createForDelivery(
        Warehouse $warehouse,
        Product $product,
        int $quantity,
        DeliveryProduct $deliveryProduct
    ): self {
        return new self($warehouse, $product, $quantity, ProductMovementType::In, null, $deliveryProduct);
    }

    public static function createForPurchaseOrder(
        Warehouse $warehouse,
        Product $product,
        int $quantity,
        PurchaseOrderProduct $purchaseOrderProduct
    ): self {
        return new self($warehouse, $product, $quantity, ProductMovementType::Out, $purchaseOrderProduct, null);
    }

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
        return $this->purchaseOrderProduct;
    }

    public function setPurchaseOrderProduct(?PurchaseOrderProduct $purchaseOrderProduct): static
    {
        $this->purchaseOrderProduct = $purchaseOrderProduct;

        return $this;
    }

    public function getDeliveryProduct(): ?DeliveryProduct
    {
        return $this->deliveryProduct;
    }

    public function setDeliveryProduct(?DeliveryProduct $deliveryProduct): static
    {
        $this->deliveryProduct = $deliveryProduct;

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

<?php

namespace App\Entity;

use App\Enum\InquiryStatus;
use App\Enum\PurchaseOrderProductStatus;
use App\Repository\PurchaseOrderProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchaseOrderProductRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_PRODUCT_ID_PURCHASE_ORDER_ID', columns: ['product_id', 'purchase_order_id'])]
class PurchaseOrderProduct extends InquiryProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'purchaseOrderProducts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PurchaseOrder $purchaseOrder = null;

    #[ORM\Column(enumType: PurchaseOrderProductStatus::class)]
    private ?PurchaseOrderProductStatus $status = null;

    /**
     * @var Collection<int, ProductMovement>
     */
    #[ORM\OneToMany(targetEntity: ProductMovement::class, mappedBy: 'purchaseOrderProduct')]
    private Collection $productMovements;

    public function __construct(
        PurchaseOrder $purchaseOrder,
        PurchaseOrderProductStatus $status,
        Product $product,
        int $quantity
    ) {
        $this->purchaseOrder = $purchaseOrder;
        $this->status = $status;
        $this->product = $product;
        $this->quantity = $quantity;
        $this->productMovements = new ArrayCollection();
    }

    public static function create(
        PurchaseOrder $purchaseOrder,
        Product $product,
        int $quantity
    ): self {
        $purchaseOrder->assertCanModifyProduct(
            'create',
            InquiryStatus::Draft
        );
        return new self(
            $purchaseOrder,
            PurchaseOrderProductStatus::Pending,
            $product,
            $quantity
        );
    }

    public function update(int $quantity): void
    {
        $this->purchaseOrder->assertCanModifyProduct(
            'update',
            InquiryStatus::Draft
        );
        $this->quantity = $quantity;
    }

    public function delete(): void
    {
        $this->purchaseOrder->assertCanModifyProduct(
            'delete',
            InquiryStatus::Draft
        );
    }

    public function markAsPrepared(): void
    {
        $this->status = PurchaseOrderProductStatus::Prepared;
    }

    public function markAsSent(): void
    {
        $this->status = PurchaseOrderProductStatus::Sent;
    }

    public function markAsPending(): void
    {
        $this->status = PurchaseOrderProductStatus::Pending;
    }

    public function markAsCancelled(): void
    {
        $this->status = PurchaseOrderProductStatus::Cancelled;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPurchaseOrder(): ?PurchaseOrder
    {
        return $this->purchaseOrder;
    }

    public function setPurchaseOrder(?PurchaseOrder $purchaseOrder): static
    {
        $this->purchaseOrder = $purchaseOrder;

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
            $productMovement->setPurchaseOrderProduct($this);
        }

        return $this;
    }

    public function removeProductMovement(ProductMovement $productMovement): static
    {
        if ($this->productMovements->removeElement($productMovement)) {
            // set the owning side to null (unless already changed)
            if ($productMovement->getPurchaseOrderProduct() === $this) {
                $productMovement->setPurchaseOrderProduct(null);
            }
        }

        return $this;
    }

    public function getStatus(): PurchaseOrderProductStatus
    {
        return $this->status;
    }

    public function setStatus(PurchaseOrderProductStatus $status): static
    {
        $this->status = $status;

        return $this;
    }
}

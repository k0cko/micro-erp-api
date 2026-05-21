<?php

namespace App\Entity;

use App\Enum\DeliveryProductStatus;
use App\Enum\InquiryStatus;
use App\Repository\DeliveryProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeliveryProductRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_PRODUCT_ID_DELIVERY_ID', columns: ['product_id', 'delivery_id'])]
class DeliveryProduct extends InquiryProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'deliveryProducts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Delivery $delivery = null;

    #[ORM\Column(enumType: DeliveryProductStatus::class)]
    private ?DeliveryProductStatus $status = null;

    /**
     * @var Collection<int, ProductMovement>
     */
    #[ORM\OneToMany(targetEntity: ProductMovement::class, mappedBy: 'deliveryProduct')]
    private Collection $productMovements;

    public function __construct(
        Delivery $delivery,
        DeliveryProductStatus $status,
        Product $product,
        int $quantity
    ) {
        $this->delivery = $delivery;
        $this->status = $status;
        $this->product = $product;
        $this->quantity = $quantity;
        $this->productMovements = new ArrayCollection();
    }

    public static function create(
        Delivery $delivery,
        Product $product,
        int $quantity
    ): self {
        $delivery->assertCanModifyProduct('create', InquiryStatus::Draft);
        return new self(
            $delivery,
            DeliveryProductStatus::Draft,
            $product,
            $quantity
        );
    }

    public function update(int $quantity): void
    {
        $this->delivery->assertCanModifyProduct('update', InquiryStatus::Draft);
        $this->quantity = $quantity;
    }

    public function delete(): void
    {
        $this->delivery->assertCanModifyProduct(
            'delete',
            InquiryStatus::Draft
        );
    }

    public function markAsReceived(): void
    {
        $this->status = DeliveryProductStatus::Received;
    }

    public function markAsPending(): void
    {
        $this->status = DeliveryProductStatus::Pending;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDelivery(): ?Delivery
    {
        return $this->delivery;
    }

    public function setDelivery(?Delivery $delivery): static
    {
        $this->delivery = $delivery;

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
            $productMovement->setDeliveryProduct($this);
        }

        return $this;
    }

    public function removeProductMovement(ProductMovement $productMovement): static
    {
        if ($this->productMovements->removeElement($productMovement)) {
            // set the owning side to null (unless already changed)
            if ($productMovement->getDeliveryProduct() === $this) {
                $productMovement->setDeliveryProduct(null);
            }
        }

        return $this;
    }

    public function getStatus(): DeliveryProductStatus
    {
        return $this->status;
    }

    public function setStatus(DeliveryProductStatus $status): static
    {
        $this->status = $status;

        return $this;
    }
}

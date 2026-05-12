<?php

namespace App\Entity;

use App\Enum\PurchaseOrderProductStatus;
use App\Repository\PurchaseOrderProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchaseOrderProductRepository::class)]
#[ORM\HasLifecycleCallbacks]
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

    public function __construct()
    {
        $this->productMovements = new ArrayCollection();
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

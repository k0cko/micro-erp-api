<?php

namespace App\Entity;

use App\Repository\PurchaseOrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchaseOrderRepository::class)]
#[ORM\Table(name: '`purchase_order`')]
class PurchaseOrder extends Inquiry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, PurchaseOrderProduct>
     */
    #[ORM\OneToMany(targetEntity: PurchaseOrderProduct::class, mappedBy: 'purchase_order')]
    private Collection $purchaseOrderProducts;

    public function __construct()
    {
        $this->purchaseOrderProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, PurchaseOrderProduct>
     */
    public function getPurchaseOrderProducts(): Collection
    {
        return $this->purchaseOrderProducts;
    }

    public function addPurchaseOrderProduct(PurchaseOrderProduct $purchaseOrderProduct): static
    {
        if (!$this->purchaseOrderProducts->contains($purchaseOrderProduct)) {
            $this->purchaseOrderProducts->add($purchaseOrderProduct);
            $purchaseOrderProduct->setPurchaseOrder($this);
        }

        return $this;
    }

    public function removePurchaseOrderProduct(PurchaseOrderProduct $purchaseOrderProduct): static
    {
        if ($this->purchaseOrderProducts->removeElement($purchaseOrderProduct)) {
            // set the owning side to null (unless already changed)
            if ($purchaseOrderProduct->getPurchaseOrder() === $this) {
                $purchaseOrderProduct->setPurchaseOrder(null);
            }
        }

        return $this;
    }
}

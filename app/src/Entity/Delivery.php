<?php

namespace App\Entity;

use App\Repository\DeliveryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeliveryRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Delivery extends Inquiry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'deliveries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Contractor $contractor = null;

    #[ORM\ManyToOne(inversedBy: 'deliveries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'deliveries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Warehouse $warehouse = null;

    /**
     * @var Collection<int, DeliveryProduct>
     */
    #[ORM\OneToMany(targetEntity: DeliveryProduct::class, mappedBy: 'delivery')]
    private Collection $deliveryProducts;

    public function __construct()
    {
        $this->deliveryProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, DeliveryProduct>
     */
    public function getDeliveryProducts(): Collection
    {
        return $this->deliveryProducts;
    }

    public function addDeliveryProduct(DeliveryProduct $deliveryProduct): static
    {
        if (!$this->deliveryProducts->contains($deliveryProduct)) {
            $this->deliveryProducts->add($deliveryProduct);
            $deliveryProduct->setDelivery($this);
        }

        return $this;
    }

    public function removeDeliveryProduct(DeliveryProduct $deliveryProduct): static
    {
        if ($this->deliveryProducts->removeElement($deliveryProduct)) {
            // set the owning side to null (unless already changed)
            if ($deliveryProduct->getDelivery() === $this) {
                $deliveryProduct->setDelivery(null);
            }
        }

        return $this;
    }

        public function getContractor(): ?Contractor
    {
        return $this->contractor;
    }

    public function setContractor(?Contractor $contractor): static
    {
        $this->contractor = $contractor;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
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
}

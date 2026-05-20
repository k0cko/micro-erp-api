<?php

namespace App\Entity;

use App\DTO\Delivery\DeliveryInput;
use App\Enum\InquiryStatus;
use App\Event\DeliveryCompletedEvent;
use App\Exception\InvalidStatusException;
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

    public function __construct(
        \DateTimeImmutable $date,
        InquiryStatus $status,
        Contractor $contractor,
        User $user,
        Warehouse $warehouse
    ) {
        $this->date = $date;
        $this->status = $status;
        $this->contractor = $contractor;
        $this->user = $user;
        $this->warehouse = $warehouse;
        $this->deliveryProducts = new ArrayCollection();
    }

    public static function create(DeliveryInput $input, User $user, Contractor $contractor, Warehouse $warehouse): self
    {
        return new self(
            $input->date,
            InquiryStatus::Draft,
            $contractor,
            $user,
            $warehouse
        );
    }

    public function update(DeliveryInput $input, Contractor $contractor, Warehouse $warehouse): void
    {
        $this->assertCanModifyEntity(
            'Delivery',
            'update',
            InquiryStatus::Draft
        );
        $this->date = $input->date;
        $this->contractor = $contractor;
        $this->warehouse = $warehouse;
    }

    public function delete(): void
    {
        $this->assertCanModifyEntity(
            'Delivery',
            'delete',
            InquiryStatus::Draft
        );
    }

    private array $events = [];
    public function complete(): void
    {
        $this->assertCanModifyEntity('Delivery', 'complete', InquiryStatus::InProgress);
        $this->status = InquiryStatus::Completed;

        $this->events[] = new DeliveryCompletedEvent($this);
    }

    public function releaseEvents(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }

    public function assertCanModifyProduct(string $action, InquiryStatus ...$allowed): void
    {
        if (!$this->isStatusAllowed($allowed)) {
            throw InvalidStatusException::forInvalidAction('Delivery products', $action, 'delivery', array_map(fn($status) => $status->label(), $allowed));
        }
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

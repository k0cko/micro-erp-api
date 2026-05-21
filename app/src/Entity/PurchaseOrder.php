<?php

namespace App\Entity;

use App\DTO\PurchaseOrder\PurchaseOrderInput;
use App\Enum\InquiryStatus;
use App\Enum\PurchaseOrderProductStatus;
use App\Event\PurchaseOrderCompletedEvent;
use App\Exception\BusinessRuleViolationException;
use App\Exception\InvalidStatusException;
use App\Repository\PurchaseOrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchaseOrderRepository::class)]
#[ORM\Table(name: '`purchase_order`')]
#[ORM\HasLifecycleCallbacks]
class PurchaseOrder extends Inquiry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'purchaseOrders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Contractor $contractor = null;

    #[ORM\ManyToOne(inversedBy: 'purchaseOrders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'purchaseOrders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Warehouse $warehouse = null;

    /**
     * @var Collection<int, PurchaseOrderProduct>
     */
    #[ORM\OneToMany(targetEntity: PurchaseOrderProduct::class, mappedBy: 'purchaseOrder')]
    private Collection $purchaseOrderProducts;
 
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
        $this->purchaseOrderProducts = new ArrayCollection();
    }

    public static function create(PurchaseOrderInput $input, User $user, Contractor $contractor, Warehouse $warehouse): self
    {
        return new self(
            $input->date,
            InquiryStatus::Draft,
            $contractor,
            $user,
            $warehouse
        );
    }

    public function update(PurchaseOrderInput $input, Contractor $contractor, Warehouse $warehouse): void
    {
        $this->assertCanModifyEntity(
            'Purchase order',
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
            'Purchase order',
            'delete',
            InquiryStatus::Draft
        );
    }

    private array $events = [];
    public function complete(): void
    {
        $this->assertProductsStatus('completed');
        $this->assertCanModifyEntity('Purchase order', 'complete', InquiryStatus::InProgress);
        $this->status = InquiryStatus::Completed;

        $this->events[] = new PurchaseOrderCompletedEvent($this);
    }

    public function start(): void
    {
        $this->assertHasProducts('started');
        $this->assertCanModifyEntity('Purchase order', 'starte', InquiryStatus::Draft);
        $this->status = InquiryStatus::InProgress;
    }

    public function cancel(): void
    {
        $this->assertCanModifyEntity('Purchase order', 'starte', InquiryStatus::Draft, InquiryStatus::InProgress);
        $this->status = InquiryStatus::Cancelled;
    }

    public function releaseEvents(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }

    public function assertProductsStatus(string $action): void
    {
        $hasPendingProducts = $this->purchaseOrderProducts->exists(fn(int $_, PurchaseOrderProduct $purchaseOrderProduct) =>
            $purchaseOrderProduct->getStatus() === PurchaseOrderProductStatus::Pending
        );

        if ($hasPendingProducts) {
            throw BusinessRuleViolationException::forInvalidProductStatus('Purchase order', $action, PurchaseOrderProductStatus::Prepared->label());
        }
    }

    public function assertHasProducts(string $action): void
    {
        if ($this->purchaseOrderProducts->isEmpty()) {
            throw BusinessRuleViolationException::forEmptyProductList('Purchase order', $action);
        }
    }

    public function assertCanModifyProduct(string $action, InquiryStatus ...$allowed): void
    {
        if (!$this->isStatusAllowed($allowed)) {
            throw InvalidStatusException::forInvalidAction('Purchase order products', $action, 'purchase order', array_map(fn($status) => $status->label(), $allowed));
        }
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

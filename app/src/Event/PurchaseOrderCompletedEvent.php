<?php

namespace App\Event;

use App\Entity\PurchaseOrder;
use Symfony\Contracts\EventDispatcher\Event;

class PurchaseOrderCompletedEvent extends Event
{
    public function __construct(
        private PurchaseOrder $purchaseOrder
    ) {}

    public function getPurchaseOrder(): PurchaseOrder
    {
        return $this->purchaseOrder;
    }
}
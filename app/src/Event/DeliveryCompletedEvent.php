<?php

namespace App\Event;

use App\Entity\Delivery;
use Symfony\Contracts\EventDispatcher\Event;

class DeliveryCompletedEvent extends Event
{
    public function __construct(
        private Delivery $delivery
    ) {}

    public function getDelivery(): Delivery
    {
        return $this->delivery;
    }
}
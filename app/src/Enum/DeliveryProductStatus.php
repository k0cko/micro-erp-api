<?php

namespace App\Enum;

use App\Enum\Trait\EnumTrait;

enum DeliveryProductStatus: string
{
    use EnumTrait;

    case Pending = 'pending';
    case Received = 'received';
    case Cancelled = 'cancelled';
}

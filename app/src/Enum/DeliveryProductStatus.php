<?php

namespace App\Enum;

use App\Enum\Trait\EnumTrait;

enum DeliveryProductStatus: string
{
    use EnumTrait;

    case Draft = 'draft';
    case Pending = 'pending';
    case Received = 'received';
    case Cancelled = 'cancelled';
}

<?php

namespace App\Enum;

use App\Enum\Trait\EnumTrait;

enum PurchaseOrderProductStatus: string
{
    use EnumTrait;

    case Pending = 'pending';
    case Prepared = 'prepared';
    case Cancelled = 'cancelled';
}

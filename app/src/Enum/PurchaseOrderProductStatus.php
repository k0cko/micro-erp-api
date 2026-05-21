<?php

namespace App\Enum;

use App\Enum\Trait\EnumTrait;

enum PurchaseOrderProductStatus: string
{
    use EnumTrait;

    case Draft = 'draft';
    case Pending = 'pending';
    case Prepared = 'prepared';
    case Sent = 'sent';
    case Cancelled = 'cancelled';
}

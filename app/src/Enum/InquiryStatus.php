<?php

namespace App\Enum;

use App\Enum\Trait\EnumTrait;

enum InquiryStatus: string
{
    use EnumTrait;

    case Draft = 'draft';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
}

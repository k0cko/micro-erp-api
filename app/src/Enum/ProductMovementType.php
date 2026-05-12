<?php

namespace App\Enum;

use App\Enum\Trait\EnumTrait;

enum ProductMovementType: string
{
    use EnumTrait;

    case In = 'in';
    case Out = 'out';
}

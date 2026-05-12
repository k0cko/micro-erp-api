<?php

namespace App\Enum;

use App\Enum\Trait\EnumTrait;

enum ContractorType: string
{
    use EnumTrait;

    case Client = 'client';
    case Supplier = 'supplier';
}

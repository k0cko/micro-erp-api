<?php

namespace App\Enum;

use App\Enum\Trait\EnumTrait;

enum UserRole: string
{
    use EnumTrait;

    case Admin = 'admin';
    case Worker = 'worker';
}

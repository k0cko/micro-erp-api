<?php

namespace App\Enum;

use App\Enum\Trait\EnumTrait;

enum UserRole: string
{
    use EnumTrait;

    case SuperAdmin = 'super_admin';
    case Admin = 'admin';
    case Worker = 'worker';
}

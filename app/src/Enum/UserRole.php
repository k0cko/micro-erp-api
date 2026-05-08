<?php

namespace App\Enum;

enum UserRole: string {
    case Admin = 'admin';
    case Worker = 'worker';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match($this)
        {
            self::Admin => 'Admin',
            self::Worker => 'Worker',
        };
    }
}
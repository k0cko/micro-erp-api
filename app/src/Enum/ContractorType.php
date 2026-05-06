<?php

namespace App\Enum;

enum ContractorType: string {
    case Client = 'client';
    case Supplier = 'supplier';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match($this)
        {
            self::Client => 'Client',
            self::Supplier => 'Supplier',
        };
    }
}
<?php

namespace App\Enum;

enum InquiryProductStatus: string {
    case Pending = 'Pending';
    case Prepared = 'Prepared';
    case Cancelled = 'Cancelled';
}
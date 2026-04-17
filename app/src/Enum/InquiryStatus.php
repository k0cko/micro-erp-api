<?php

namespace App\Enum;

enum InquiryStatus: string {
    case Draft = 'Draft';
    case In_progress = 'In progress';
    case Completed = 'Completed';
    case Cancelled = 'Cancelled';
}
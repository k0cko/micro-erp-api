<?php

namespace App\Exception;

final class OldPasswordMismatchException extends \RuntimeException
{
    public static function create(): self
    {
        return new self("Old password doesn't match the current password");
    }
}
<?php

namespace App\Exception;

final class InvalidStatusException extends \RuntimeException
{
    public static function create(string $entity, string $action, string $status): self
    {
        return new self(sprintf('%s can only be %s when in %s status', $entity, $action, $status));
    }
}

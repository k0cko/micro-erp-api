<?php

namespace App\Exception;

final class InvalidStatusException extends \RuntimeException
{
    public static function create(string $entity, string $action, array $allowedStatuses): self
    {
        return new self(sprintf('%s can only be %sd when in %s status', $entity, $action, join(' and ', $allowedStatuses)));
    }

    public static function forInvalidAction(string $entity, string $action, string $subject, array $allowedStatuses): self
    {
        return new self(sprintf('%s can only be %sd if the %s is in %s status', $entity, $action, $subject, join(' and ', $allowedStatuses)));
    }
}

<?php

namespace App\Exception;

final class BusinessRuleViolationException extends \RuntimeException
{
    public static function forEmptyProductList(string $entity, string $action): self
    {
        return new self(sprintf('%s can only be %sd if there are products in it.', $entity, $action));
    }
}

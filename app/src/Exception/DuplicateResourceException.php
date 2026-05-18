<?php

namespace App\Exception;

final class DuplicateResourceException extends \RuntimeException
{
    public static function forField(
        string $entity,
        string $field,
        string $value,
    ): self
    {
        return new self(
            sprintf('%s with %s "%s" already exists.', $entity, $field, $value)
        );
    }

    public static function forDuplicateProduct(
        string $product,
        string $entity,
    ): self
    {
        return new self(
            sprintf('%s already exists in the %s', $product, $entity)
        );
    }
}

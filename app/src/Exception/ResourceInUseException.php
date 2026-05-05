<?php

namespace App\Exception;

final class ResourceInUseException extends \RuntimeException
{
    public static function forEntity(
        string $entity,
        string $usedByEntity
    ): self
    {
        return new self(
            sprintf('Cannot delete %s. It has associated %ss.', $entity, $usedByEntity)
        );
    }    
}

<?php

namespace App\Exception;

final class BusinessRuleViolationException extends \RuntimeException
{
    public static function forEmptyProductList(string $entity, string $action): self
    {
        return new self(sprintf('%s can only be %s if there are products in it.', $entity, $action));
    }

    public static function forInvalidProductStatus(string $entity, string $action, string $allowedProductStatus): self
    {
        return new self(sprintf('%s can only be %s if all non-cancelled products are marked as "%s"', $entity, $action, $allowedProductStatus));
    }

    public static function forInvalidContractorType(string $contractorType): self
    {
        return new self(sprintf('Contractor should be of type %s.', $contractorType));
    }
}

<?php

namespace App\Doctrine;

use Doctrine\ORM\Query\Filter\SQLFilter;
use \Doctrine\ORM\Mapping\ClassMetadata;

class SoftDeleteFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, string $targetTableAlias): string
    {
        if (!$targetEntity->hasField('deletedAt')) {
            return '';
        }

        return sprintf('%s.deleted_at IS NULL', $targetTableAlias);
    }
}
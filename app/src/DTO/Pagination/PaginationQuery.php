<?php

namespace App\DTO\Pagination;

use Symfony\Component\Validator\Constraints as Assert;

class PaginationQuery
{
    public function __construct(
        #[Assert\Positive]
        public int $page = PaginatedResult::DEFAULT_PAGE,
        #[Assert\Range(min: 25, max: 100)]
        public int $limit = PaginatedResult::DEFAULT_LIMIT,
    ) {}
}
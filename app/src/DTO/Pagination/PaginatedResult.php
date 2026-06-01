<?php

namespace App\DTO\Pagination;

final readonly class PaginatedResult
{
    public const int DEFAULT_PAGE = 1;
    public const int DEFAULT_LIMIT = 25;
    
    public int $pages;

    public function __construct(
        public array $items,
        public int $total,
        public int $page,
        public int $limit,
    ) {
        $this->pages = (int) ceil($this->total / $this->limit);
    }
}
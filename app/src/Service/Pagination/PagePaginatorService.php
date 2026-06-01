<?php

namespace App\Service\Pagination;

use App\DTO\Pagination\PaginatedResult;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PagePaginatorService
{
    private const int MAX_LIMIT = 100;

    public function paginate(QueryBuilder $queryBuilder, callable $mapper, int $page = PaginatedResult::DEFAULT_PAGE, int $limit = PaginatedResult::DEFAULT_LIMIT): PaginatedResult
    {
        $page = max($page, PaginatedResult::DEFAULT_PAGE);
        // Guard from limit being 0 or negative
        $limit = max(1, min($limit, self::MAX_LIMIT));

        $offset = ($page - 1) * $limit;

        $queryBuilder
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        $paginator = new Paginator($queryBuilder);
        $totalResults = $paginator->count();

        $items = [];
        foreach ($paginator as $item) {
            $items[] = $mapper($item);
        }

        return new PaginatedResult($items, $totalResults, $page, $limit);
    }
}
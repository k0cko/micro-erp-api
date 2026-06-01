<?php

namespace App\Controller;

use App\DTO\Pagination\PaginatedResult;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class AbstractApiController extends AbstractController
{
    public function jsonPaginated(PaginatedResult $result): JsonResponse
    {
        return $this->json([
            'data' => $result->items,
            'meta' => [
                'total' => $result->total,
                'page' => $result->page,
                'limit' => $result->limit,
                'pages' => $result->pages,
            ]
        ], JsonResponse::HTTP_OK, [
            'X-Envelope-Handled' => 'true'
        ]);
    }
}
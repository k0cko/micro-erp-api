<?php

namespace App\Service\User;

use App\DTO\Pagination\PaginatedResult;
use App\Mapper\User\UserResponseMapper;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Service\Pagination\PagePaginatorService;

final class ListUserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly PagePaginatorService $paginator,
    ) {}

    public function execute(int $page, int $limit): PaginatedResult
    {
        return $this->paginator->paginate(
            $this->userRepository->createPaginatedQueryBuilder(),
            fn(User $user) => UserResponseMapper::map($user),
            $page,
            $limit,
        );
    }
}
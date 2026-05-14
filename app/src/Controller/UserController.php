<?php

namespace App\Controller;

use App\DTO\User\ChangeUserPasswordInput;
use App\DTO\User\CreateUserInput;
use App\DTO\User\UpdateUserInput;
use App\Entity\User;
use App\Exception\DuplicateResourceException;
use App\Exception\OldPasswordMismatchException;
use App\Service\User\ChangeUserPasswordService;
use App\Service\User\CreateUserService;
use App\Service\User\DeleteUserService;
use App\Service\User\ListUserService;
use App\Service\User\UpdateUserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/users')]
#[IsGranted('ROLE_WORKER')]
final class UserController extends AbstractController
{
    public function __construct(
        private readonly CreateUserService $createUserService,
        private readonly UpdateUserService $updateUserService,
        private readonly ChangeUserPasswordService $changeUserPasswordService,
        private readonly ListUserService $listUserService,
        private readonly DeleteUserService $deleteUserService
    ) {}

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json($this->listUserService->execute(), JsonResponse::HTTP_OK);
    }

    #[Route('', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function create(
        #[MapRequestPayload] CreateUserInput $input,
    ): JsonResponse
    {
        try {
            $id = $this->createUserService->execute($input);
        } catch (DuplicateResourceException $e) {
            return $this->json(['error' => $e->getMessage()], JsonResponse::HTTP_CONFLICT);
        }

        return $this->json(['id' => $id], JsonResponse::HTTP_CREATED);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(
        #[CurrentUser] User $user,
        #[MapRequestPayload] UpdateUserInput $input
    ): JsonResponse
    {
        $userResponse = $this->updateUserService->execute($user, $input);

        return $this->json($userResponse, JsonResponse::HTTP_OK);
    }

    #[Route('/change-password', methods: ['PUT'])]
    public function changePassword(
        #[CurrentUser] User $user,
        #[MapRequestPayload] ChangeUserPasswordInput $input
    ): JsonResponse
    {
        try {
            $this->changeUserPasswordService->execute($input, $user);
        } catch (OldPasswordMismatchException $e) {
            return $this->json(['error' => $e->getMessage()], JsonResponse::HTTP_UNAUTHORIZED);
        }

        return $this->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
    
    #[Route('/{id}', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(
        User $user
    ): JsonResponse
    {
        $this->deleteUserService->execute($user);
        return $this->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
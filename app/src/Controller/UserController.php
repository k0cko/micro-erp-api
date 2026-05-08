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
use App\Service\User\UpdateUserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/users')]
final class UserController extends AbstractController
{
    public function __construct(
        private readonly CreateUserService $createUserService,
        private readonly UpdateUserService $updateUserService,
        private readonly ChangeUserPasswordService $changeUserPasswordService,
    ) {}

    #[Route('', methods: ['POST'])]
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

    #[Route('/{id}', methods: ['PUT'])] // TODO: Update route to not use id, but rather use $this->getUser() in order to prevent IDOR vulnerability. /me
    public function update(
        User $user,
        #[MapRequestPayload] UpdateUserInput $input
    ): JsonResponse
    {
        $userResponse = $this->updateUserService->execute($user, $input);

        return $this->json($userResponse, JsonResponse::HTTP_OK);
    }

    #[Route('/{id}/change-password', methods: ['PUT'])] // TODO: Update route to not use id, but rather use $this->getUser() in order to prevent IDOR vulnerability. /change-password
    public function changePassword(
        User $user,
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
}
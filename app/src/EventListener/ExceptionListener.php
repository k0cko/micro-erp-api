<?php

namespace App\EventListener;

use App\Exception\DuplicateResourceException;
use App\Exception\InvalidStatusException;
use App\Exception\OldPasswordMismatchException;
use App\Exception\ResourceInUseException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

#[AsEventListener]
class ExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof HttpExceptionInterface) {
            $response = match ($exception::class) {
                DuplicateResourceException::class => $this->getResponse($exception->getMessage(), JsonResponse::HTTP_CONFLICT),
                InvalidStatusException::class => $this->getResponse($exception->getMessage(), JsonResponse::HTTP_BAD_REQUEST),
                OldPasswordMismatchException::class => $this->getResponse($exception->getMessage(), JsonResponse::HTTP_BAD_REQUEST),
                ResourceInUseException::class => $this->getResponse($exception->getMessage(), JsonResponse::HTTP_CONFLICT),
                default => $this->getResponse('Ooops, something went wrong.', JsonResponse::HTTP_INTERNAL_SERVER_ERROR),
            };
            
            $event->setResponse($response);
        }
    }

    private function getResponse(string $message, int $errorCode): JsonResponse
    {
        return new JsonResponse(
            ['error' => $message],
            $errorCode
        );
    }
}

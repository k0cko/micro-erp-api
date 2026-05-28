<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

#[AsEventListener]
class KernelResponseListener
{
    public function __invoke(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $response = $event->getResponse();

        if ($response->headers->get('Content-Type') !== 'application/json') {
            return;
        }

        if ($response->getStatusCode() >= 400) {
            return;
        }

        try {
            $contentData = json_decode(json: $response->getContent(), associative: true, flags: JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return;
        }

        $response->setContent(json_encode(['data' => $contentData]));
    }
}

<?php

namespace App\EventSubscriber;

use App\Exception\NotAllowedException;
use App\Exception\NotFoundException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;

final class ExceptionEventSubscriber implements EventSubscriberInterface
{
    private $logger;

    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }

    private const EXCEPTION_RESPONSE_HTTP_CODE_MAP = [
        NotFoundException::class => Response::HTTP_NOT_FOUND,
        UnprocessableEntityHttpException::class => Response::HTTP_BAD_REQUEST,
        NotAllowedException::class => Response::HTTP_METHOD_NOT_ALLOWED,
    ];

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => ['__invoke']];
    }

    public function __invoke(ExceptionEvent $event) : void
    {
        $throwable = $event->getThrowable();
        $response = new JsonResponse(
            [
                'error' => $throwable->getMessage(),
            ],
            $this->httpCode($throwable),
            [
                'Content-Type' => 'application/json',
            ]
        );

        $event->setResponse($response);
    }

    private function httpCode(Throwable $throwable): int
    {
        $throwableClass = $throwable::class;
        if (array_key_exists($throwableClass, self:: EXCEPTION_RESPONSE_HTTP_CODE_MAP)) {
            return self:: EXCEPTION_RESPONSE_HTTP_CODE_MAP[$throwableClass];
        }
        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }
}
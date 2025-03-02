<?php

declare(strict_types=1);

namespace Cook\Component\Http\Exception;

use Cook\Component\Http\Response;
use Throwable;

/**
 * Handles exceptions and converts them into HTTP responses.
 */
final class ExceptionHandler
{
    /**
     * Handles a thrown exception and creates a corresponding HTTP response.
     *
     * @param Throwable $exception The caught exception.
     * @return Response HTTP response.
     * @throws \JsonException
     */
    public function handle(Throwable $exception): Response
    {
        $statusCode = $this->determineStatusCode($exception);
        $errorMessage = $this->createErrorMessage($exception);

        return new Response($errorMessage, $statusCode, ['Content-Type' => 'application/json']);
    }

    /**
     * Determines the HTTP status code based on the exception type.
     *
     * @param Throwable $exception The caught exception.
     * @return int HTTP status code.
     */
    private function determineStatusCode(Throwable $exception): int
    {
        return match (true) {
            $exception instanceof BadRequestException => 400,
            $exception instanceof NotFoundException => 404,
            default => 500,
        };
    }

    /**
     * Creates a JSON error message from the exception details.
     *
     * @param Throwable $exception The caught exception.
     * @return string JSON error message.
     * @throws \JsonException
     */
    private function createErrorMessage(Throwable $exception): string
    {
        return json_encode([
            'error' => [
                'code' => $exception->getCode(),
                'type' => get_class($exception),
                'message' => $exception->getMessage(),
            ]
        ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
    }
}

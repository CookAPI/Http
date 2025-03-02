<?php

declare(strict_types=1);

namespace Tests\Cook\Component\Http;

use Cook\Component\Http\Exception\ExceptionHandler;
use Cook\Component\Http\Exception\BadRequestException;
use Cook\Component\Http\Exception\NotFoundException;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the ExceptionHandler class.
 */
final class ExceptionHandlerTest extends TestCase
{
    private ExceptionHandler $exceptionHandler;

    protected function setUp(): void
    {
        $this->exceptionHandler = new ExceptionHandler();
    }

    /**
     * Test handling of a generic exception.
     * @throws \JsonException
     */
    public function testHandleGenericException(): void
    {
        $exception = new \Exception('Generic error', 100);
        $response = $this->exceptionHandler->handle($exception);

        $expectedJson = json_encode([
            'error' => [
                'code' => 100,
                'type' => 'Exception',
                'message' => 'Generic error'
            ]
        ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);

        $this->assertSame(500, $response->getStatusCode());
        $this->assertSame($expectedJson, $response->getContent());
        $this->assertSame('application/json', $response->getHeader('Content-Type'));
    }

    /**
     * Test handling of a BadRequestException.
     * @throws \JsonException
     */
    public function testHandleBadRequestException(): void
    {
        $exception = new BadRequestException('Invalid input', 400);
        $response = $this->exceptionHandler->handle($exception);

        $expectedJson = json_encode([
            'error' => [
                'code' => 400,
                'type' => BadRequestException::class,
                'message' => 'Invalid input'
            ]
        ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertSame($expectedJson, $response->getContent());
        $this->assertSame('application/json', $response->getHeader('Content-Type'));
    }

    /**
     * Test handling of a NotFoundException.
     * @throws \JsonException
     */
    public function testHandleNotFoundException(): void
    {
        $exception = new NotFoundException('Resource not found', 404);
        $response = $this->exceptionHandler->handle($exception);

        $expectedJson = json_encode([
            'error' => [
                'code' => 404,
                'type' => NotFoundException::class,
                'message' => 'Resource not found'
            ]
        ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);

        $this->assertSame(404, $response->getStatusCode());
        $this->assertSame($expectedJson, $response->getContent());
        $this->assertSame('application/json', $response->getHeader('Content-Type'));
    }
}

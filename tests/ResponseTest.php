<?php

declare(strict_types=1);

use Cook\Component\Http\Response;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the Response class.
 */
final class ResponseTest extends TestCase
{
    /**
     * Test setting and getting the response content.
     */
    public function testResponseContent(): void
    {
        $response = new Response('Hello, World!', 200);
        $this->assertSame('Hello, World!', $response->getContent());
    }

    /**
     * Test setting and retrieving the status code.
     */
    public function testResponseStatusCode(): void
    {
        $response = new Response('Not Found', 404);
        $this->assertSame(404, $response->getStatusCode());
    }

    /**
     * Test adding and retrieving headers.
     */
    public function testResponseHeaders(): void
    {
        $response = new Response('OK', 200, ['Content-Type' => 'application/json']);

        $this->assertSame('application/json', $response->getHeader('Content-Type'));
        $this->assertNull($response->getHeader('Non-Existent-Header'));
    }

    /**
     * Test JSON response format.
     * @throws \JsonException
     */
    public function testJsonResponse(): void
    {
        $data = ['success' => true, 'message' => 'Operation completed'];
        $jsonResponse = new Response(json_encode($data, JSON_THROW_ON_ERROR), 200, ['Content-Type' => 'application/json']);

        $this->assertJson($jsonResponse->getContent());
        $this->assertSame('application/json', $jsonResponse->getHeader('Content-Type'));
    }
}

<?php

declare(strict_types=1);

use Cook\Component\Http\Server;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the Server class.
 */
final class ServerTest extends TestCase
{
    private Server $server;

    protected function setUp(): void
    {
        $this->server = new Server();
        $_SERVER = []; // Réinitialisation avant chaque test
    }

    /**
     * Test retrieving an existing HTTP header.
     */
    public function testGetExistingHeader(): void
    {
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer token123';

        $this->assertSame('Bearer token123', $this->server->getHeader('Authorization'));
    }

    /**
     * Test retrieving an existing header with different case formatting.
     */
    public function testGetHeaderCaseInsensitive(): void
    {
        $_SERVER['HTTP_CONTENT_TYPE'] = 'application/json';

        $this->assertSame('application/json', $this->server->getHeader('content-type'));
        $this->assertSame('application/json', $this->server->getHeader('CONTENT-TYPE'));
    }

    /**
     * Test retrieving a non-existent HTTP header.
     */
    public function testGetNonExistentHeader(): void
    {
        $this->assertNull($this->server->getHeader('Non-Existent-Header'));
    }
}

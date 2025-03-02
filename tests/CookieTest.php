<?php

declare(strict_types=1);

use Cook\Component\Http\Cookie;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the Cookie class.
 */
final class CookieTest extends TestCase
{
    private Cookie $cookie;

    protected function setUp(): void
    {
        $this->cookie = new Cookie();
    }

    /**
     * Test setting a cookie with valid parameters.
     */
    public function testSetCookie(): void
    {
        $this->cookie->set('test_cookie', 'test_value', 3600, '/', 'example.com', true, true, 'Strict');

        $reflection = new \ReflectionClass(Cookie::class);
        $property = $reflection->getProperty('cookieMetadata');
        $metadata = $property->getValue($this->cookie);

        $this->assertArrayHasKey('test_cookie', $metadata);
        $this->assertSame('test_value', rawurldecode($metadata['test_cookie']['value'] ?? ''));
        $this->assertSame('/', $metadata['test_cookie']['path']);
        $this->assertSame('example.com', $metadata['test_cookie']['domain']);
        $this->assertTrue($metadata['test_cookie']['secure']);
        $this->assertTrue($metadata['test_cookie']['httponly']);
        $this->assertSame('Strict', $metadata['test_cookie']['samesite']);
    }

    /**
     * Test setting a cookie with an invalid (non-scalar) value.
     */
    public function testSetInvalidCookieValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->cookie->set('invalid_cookie', ['array_value']);
    }

    /**
     * Test deleting a cookie.
     */
    public function testDeleteCookie(): void
    {
        $this->cookie->set('delete_cookie', 'delete_value', 3600);
        $this->cookie->delete('delete_cookie');

        $reflection = new \ReflectionClass(Cookie::class);
        $property = $reflection->getProperty('cookieMetadata');
        $metadata = $property->getValue($this->cookie);

        $this->assertArrayNotHasKey('delete_cookie', $metadata);
    }
}

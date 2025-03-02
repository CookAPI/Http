<?php

declare(strict_types=1);

use Cook\Component\Http\Session;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the Session class.
 */
final class SessionTest extends TestCase
{
    private Session $session;

    protected function setUp(): void
    {
        $_SESSION = []; // Simuler une session propre
        $this->session = new Session();
    }

    /**
     * Test starting a session.
     */
    public function testStartSession(): void
    {
        $this->session->start();
        $this->assertTrue(isset($_SESSION));
    }

    /**
     * Test setting and getting session data.
     */
    public function testSetAndGetSessionValue(): void
    {
        $this->session->set('user_id', 12345);
        $this->assertSame(12345, $this->session->get('user_id'));
    }

    /**
     * Test retrieving a non-existent session value.
     */
    public function testGetNonExistentSessionValue(): void
    {
        $this->assertNull($this->session->get('missing_key'));
        $this->assertSame('default', $this->session->get('missing_key', 'default'));
    }

    /**
     * Test removing a session value.
     */
    public function testRemoveSessionValue(): void
    {
        $this->session->set('temp_key', 'temp_value');
        $this->session->remove('temp_key');
        $this->assertNull($this->session->get('temp_key'));
    }

    /**
     * Test destroying the session.
     */
    public function testDestroySession(): void
    {
        $this->session->set('user_id', 12345);
        $this->session->destroy();
        $this->assertEmpty($_SESSION);
    }
}

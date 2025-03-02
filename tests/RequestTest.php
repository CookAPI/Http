<?php

declare(strict_types=1);

use Cook\Component\Http\Exception\BadRequestException;
use Cook\Component\Http\Request;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the Request class.
 */
final class RequestTest extends TestCase
{
    private Request $request;

    protected function setUp(): void
    {
        $_GET = ['param1' => 'value1', 'param2' => 'value2'];
        $_POST = ['formField' => 'submittedValue'];
        $_SERVER = [
            'REQUEST_METHOD' => 'POST',
            'HTTP_AUTHORIZATION' => 'Bearer token123'
        ];
        $_FILES = [
            'uploadedFile' => [
                'name' => 'test.txt',
                'type' => 'text/plain',
                'size' => 100,
                'tmp_name' => '/tmp/php12345',
                'error' => UPLOAD_ERR_OK
            ]
        ];

        $this->request = Request::createFromGlobals();
    }

    /**
     * Test retrieving GET parameters.
     * @throws BadRequestException
     */
    public function testGetParameter(): void
    {
        $this->assertSame('value1', $this->request->query->get('param1'));
        $this->assertSame('value2', $this->request->query->get('param2'));
        $this->assertNull($this->request->query->get('nonExistentParam'));
    }

    /**
     * Test retrieving POST parameters.
     * @throws BadRequestException
     */
    public function testPostParameter(): void
    {
        $this->assertSame('submittedValue', $this->request->request->get('formField'));
        $this->assertNull($this->request->request->get('unknownField'));
    }

    /**
     * Test retrieving request method.
     */
    public function testGetMethod(): void
    {
        $this->assertSame('POST', $this->request->server->getRequestMethod());
    }

    /**
     * Test retrieving HTTP headers.
     */
    public function testGetHeader(): void
    {
        $this->assertSame('Bearer token123', $this->request->server->getHeader('Authorization'));
        $this->assertNull($this->request->server->getHeader('NonExistentHeader'));
    }

    /**
     * Test retrieving uploaded files.
     */
    public function testGetFile(): void
    {
        $file = $this->request->file->get('uploadedFile');

        $this->assertNotNull($file);
        $this->assertSame('test.txt', $file['name']);
        $this->assertSame('text/plain', $file['type']);
        $this->assertSame(100, $file['size']);
    }
}

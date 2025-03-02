<?php

declare(strict_types=1);

use Cook\Component\Http\MiddlewareStack;
use Cook\Component\Http\Request;
use Cook\Component\Http\Response;
use Cook\Component\Http\MiddlewareInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the MiddlewareStack class.
 */
final class MiddlewareStackTest extends TestCase
{
    private MiddlewareStack $middlewareStack;

    protected function setUp(): void
    {
        $this->middlewareStack = new MiddlewareStack();
    }

    /**
     * Mock middleware returning a modified response.
     * @throws Exception
     */
    public function testMiddlewareExecution(): void
    {
        $middleware1 = new class implements MiddlewareInterface {
            public function handle(Request $request, callable $next): Response
            {
                $response = $next($request);
                return new Response($response->getContent() . 'Middleware1 ', 200);
            }
        };

        $middleware2 = new class implements MiddlewareInterface {
            public function handle(Request $request, callable $next): Response
            {
                $response = $next($request);
                return new Response($response->getContent() . 'Middleware2 ', 200);
            }
        };

        $this->middlewareStack->addMiddleware($middleware1);
        $this->middlewareStack->addMiddleware($middleware2);

        $request = $this->createMock(Request::class);
        $response = $this->middlewareStack->handle($request);

        $this->assertSame('No middleware processed the requestMiddleware2 Middleware1 ', $response->getContent());
    }

    /**
     * Test execution with an empty middleware stack.
     * @throws Exception
     */
    public function testEmptyMiddlewareStack(): void
    {
        $request = $this->createMock(Request::class);
        $response = $this->middlewareStack->handle($request);

        $this->assertSame('No middleware processed the request', $response->getContent());
        $this->assertSame(404, $response->getStatusCode());
    }

    /**
     * Test middleware that stops propagation.
     * @throws Exception
     */
    public function testMiddlewareStopsPropagation(): void
    {
        $middleware1 = new class implements MiddlewareInterface {
            public function handle(Request $request, callable $next): Response
            {
                return new Response('Final response', 200);
            }
        };

        $middleware2 = new class implements MiddlewareInterface {
            public function handle(Request $request, callable $next): Response
            {
                $response = $next($request);
                return new Response($response->getContent() . 'Middleware2 ', 200);
            }
        };

        $this->middlewareStack->addMiddleware($middleware1);
        $this->middlewareStack->addMiddleware($middleware2);

        $request = $this->createMock(Request::class);
        $response = $this->middlewareStack->handle($request);

        $this->assertSame('Final response', $response->getContent());
    }
}

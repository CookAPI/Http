<?php

namespace Cook\Component\Http;

use SplQueue;

/**
 * Manages a queue of middleware for HTTP requests.
 */
final class MiddlewareStack
{
    /**
     * @var SplQueue Holds middleware instances.
     */
    private SplQueue $middlewares;

    public function __construct()
    {
        $this->middlewares = new SplQueue();
    }

    /**
     * Adds middleware to the queue.
     *
     * @param MiddlewareInterface $middleware Middleware instance.
     * @return self
     */
    public function addMiddleware(MiddlewareInterface $middleware): self
    {
        $this->middlewares->enqueue($middleware);
        return $this;
    }

    /**
     * Processes the request through the middleware stack.
     *
     * @param Request $request HTTP request.
     * @return Response HTTP response.
     */
    public function handle(Request $request): Response
    {
        $middlewareQueue = clone $this->middlewares;

        $handler = static function () {
            return new Response('No middleware processed the request', 404);
        };

        while (!$middlewareQueue->isEmpty()) {
            $middleware = $middlewareQueue->pop();
            $handler = static fn($req) => $middleware->handle($req, $handler);
        }

        return $handler($request);
    }
}

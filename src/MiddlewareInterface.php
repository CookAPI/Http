<?php

declare(strict_types=1);

namespace Cook\Component\Http;

/**
 * MiddlewareInterface defines a contract for middleware classes,
 * ensuring they implement a handle method to process HTTP requests.
 */
interface MiddlewareInterface
{
    /**
     * Processes an HTTP request and returns a response.
     *
     * @param Request $request The HTTP request object.
     * @param callable $next The next middleware in the stack or the final request handler.
     * @return Response The HTTP response object.
     */
    public function handle(Request $request, callable $next): Response;
}

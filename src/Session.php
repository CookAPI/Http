<?php

declare(strict_types=1);

namespace Cook\Component\Http;

/**
 * Manages session data and provides an interface for interacting with session variables.
 * Extends the Parameter class to handle session-related key-value storage.
 */
final class Session extends Parameter
{
    /**
     * Initializes the session and starts it if not already started.
     *
     * @param array<string, mixed> $parameters Optional initial session parameters.
     */
    public function __construct(array $parameters = [])
    {
        parent::__construct($parameters);
        $this->start();
    }

    /**
     * Starts the session if it has not been started yet.
     */
    public function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Clears all session parameters.
     */
    public function clear(): void
    {
        unset($this->parameters);
    }

    /**
     * Destroys the current session and removes all session data.
     */
    public function destroy(): void
    {
        session_destroy();
    }
}

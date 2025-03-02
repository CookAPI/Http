<?php

declare(strict_types=1);

namespace Cook\Component\Http;

/**
 * Parameter class provides a base for managing key-value pairs, such as HTTP request parameters.
 */
class Parameter
{
    /**
     * Constructs the Parameter object with an optional initial set of parameters.
     *
     * @param array $parameters Initial parameters to set.
     */
    public function __construct(
        protected array $parameters = []
    ) {
    }

    /**
     * Sets a parameter by key.
     *
     * @param string $key The parameter key.
     * @param mixed $value The value to set for the given key.
     * @return Parameter Returns itself for chaining.
     */
    public function set(string $key, mixed $value): Parameter
    {
        $this->parameters[$key] = $value;

        return $this;
    }

    /**
     * Retrieves a parameter by key with an optional default value.
     *
     * @param string $key The parameter key to retrieve.
     * @param mixed $default The default value to return if the key does not exist.
     * @return mixed The parameter value or the default value.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->has($key) ? $this->parameters[$key] : $default;
    }

    /**
     * Checks if a parameter exists by key.
     *
     * @param string $key The parameter key to check.
     * @return bool True if the parameter exists, false otherwise.
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->parameters);
    }

    /**
     * Removes a parameter by key.
     *
     * @param string $key The parameter key to remove.
     */
    public function remove(string $key): void
    {
        unset($this->parameters[$key]);
    }

    /**
     * Returns all parameters.
     *
     * @return array The array of all parameters.
     */
    public function all(): array
    {
        return $this->parameters;
    }

    /**
     * Counts the number of parameters.
     *
     * @return int The number of parameters.
     */
    public function count(): int
    {
        return count($this->parameters);
    }
}

<?php

declare(strict_types=1);

namespace Cook\Component\Http;

use Cook\Component\Http\Exception\BadRequestException;

/**
 * The Method class extends the Parameter class to specifically handle HTTP request methods,
 * providing validation and storage for values associated with those methods.
 */
final class Method extends Parameter
{
    /**
     * Sets a value for a given key after validating that the value is appropriate
     * for HTTP method parameters.
     *
     * @param string $key The parameter key.
     * @param mixed $value The value to be set, which must be scalar, an array, or an instance of \Stringable.
     * @return Method Returns the current instance for method chaining.
     * @throws BadRequestException If the value does not meet the validation criteria.
     */
    public function set(string $key, mixed $value): Method
    {
        $this->validateValue($value, __METHOD__);
        $this->parameters[$key] = $value;

        return $this;
    }

    /**
     * Retrieves the value for a given key, with optional default value.
     * The method also validates the retrieved or default value.
     *
     * @param string $key The parameter key to retrieve.
     * @param mixed $default The default value to return if the key does not exist.
     * @return mixed The value associated with the key or the default value.
     * @throws BadRequestException If the retrieved or default value does not meet the validation criteria.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $this->validateValue($default, __METHOD__);
        $value = parent::get($key, $default);
        $this->validateValue($value, __METHOD__, $key);

        return $value;
    }

    /**
     * Validates that a given value is either scalar, an array, or an instance of \Stringable.
     * This ensures that all method parameters are of an expected type.
     *
     * @param mixed $value The value to validate.
     * @param string $methodName The name of the method from which this validation is called.
     * @param string $key Optional. The key associated with the value being validated.
     * @throws BadRequestException If the value does not meet the validation criteria.
     */
    private function validateValue(mixed $value, string $methodName, string $key = ''): void
    {
        if (null !== $value && !is_scalar($value) && !is_array($value) && !$value instanceof \Stringable) {
            $exceptionMessage = $key
                ? sprintf('The value for the key "%s" is not scalar or Stringable.', $key)
                : sprintf(
                    'The value passed to "%s" must be scalar, array, or Stringable. Received type: "%s".',
                    $methodName,
                    get_debug_type($value)
                );

            throw new BadRequestException($exceptionMessage);
        }
    }
}

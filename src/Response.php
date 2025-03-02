<?php

declare(strict_types=1);

namespace Cook\Component\Http;

/**
 * Represents an HTTP response, encapsulating the content, status code, and headers to be sent to the client.
 */
final class Response
{
    /**
     * @var string The response content.
     */
    private string $content;

    /**
     * @var int The HTTP status code.
     */
    private int $statusCode;

    /**
     * @var array Response headers.
     */
    private array $headers;

    /**
     * Constructs a new Response object with optional initial content, status code, and headers.
     */
    public function __construct(string $content = '', int $statusCode = 200, array $headers = [])
    {
        $this->content = $content;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }

    /**
     * Sets the content of the HTTP response.
     *
     * @param string $content The response content.
     * @return self Chainable method.
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Gets the content of the HTTP response.
     *
     * @return string Chainable method.
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Sets the HTTP status code of the response.
     *
     * @param int $statusCode The HTTP status code.
     * @return self Chainable method.
     */
    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Gets the current HTTP status code of the response.
     *
     * @return int The HTTP status code.
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Adds a header to the HTTP response.
     *
     * @param string $name The name of the header.
     * @param string $value The value of the header.
     * @return self Chainable method.
     */
    public function addHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;

        return $this;
    }

    /**
     * Sends the headers to the client. This method checks if headers have already been sent to prevent errors.
     * If the Content-Type header is not already set, it defaults to 'text/html'.
     *
     * @return self Chainable method.
     */
    public function sendHeaders(): self
    {
        if (headers_sent()) {
            return $this;
        }

        foreach ($this->headers as $name => $value) {
            header(sprintf('%s: %s', $name, $value), true, $this->statusCode);
        }

        http_response_code($this->statusCode);

        return $this;
    }

    /**
     * Retrieves a specific header value.
     *
     * @param string $name The name of the header.
     * @return string|null The header value or null if not found.
     */
    public function getHeader(string $name): ?string
    {
        return $this->headers[$name] ?? null;
    }

    /**
     * Outputs the content of the HTTP response to the client.
     *
     * @return self Chainable method.
     */
    public function sendContent(): self
    {
        echo $this->content;

        return $this;
    }

    /**
     * Sends the HTTP response to the client, including both headers and content.
     *
     * @return self Chainable method.
     */
    public function send(): self
    {
        $this->sendHeaders()->sendContent();

        return $this;
    }

    /**
     * Factory method to create a new Response instance.
     *
     * @param string $content The content of the response.
     * @param int $statusCode The HTTP status code.
     * @param array $headers The headers to be included in the response.
     * @return self A new instance of Response.
     */
    public static function create(string $content = '', int $statusCode = 200, array $headers = []): self
    {
        return new self($content, $statusCode, $headers);
    }
}

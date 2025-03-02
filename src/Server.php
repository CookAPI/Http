<?php

declare(strict_types=1);

namespace Cook\Component\Http;

/**
 * Extends the Parameter class to specifically manage server and execution environment parameters.
 */
final class Server extends Parameter
{
    /**
     * @var string Default HTTP method.
     */
    private const METHOD_GET = 'GET';

    /**
     * @var array|string[] List of trusted proxies.
     */
    private array $trustedProxies = ['192.168.1.1'];

    /**
     *  Sets a value for a server parameter.
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set(string $key, mixed $value): Server
    {
        $this->headers($key, $value);

        return $this;
    }

    /**
     * Sets a server or HTTP header value.
     *
     * @param string $key The header name or server parameter key.
     * @param string|array<string, mixed>|null $value The value to set for the header or server parameter.
     * @return Server Returns this instance for method chaining.
     */
    public function headers(string $key, string|array|null $value): Server
    {
        parent::set($key, $value);
        return $this;
    }

    /**
     * Retrieves the HTTP request method.
     *
     * @return string The request method (e.g., 'GET', 'POST'). Defaults to 'GET' if not set.
     */
    public function getRequestMethod(): string
    {
        return $this->get('REQUEST_METHOD') ?? self::METHOD_GET;
    }

    /**
     * Retrieves the request URI.
     *
     * @return string The URI which was given in order to access this page. Defaults to '/' if not set.
     */
    public function getRequestUri(): string
    {
        return $this->get('REQUEST_URI') ?? '/';
    }

    /**
     * Determines the client's IP address, taking into account trusted proxies.
     *
     * @return ?string The client's IP address, or null if it cannot be determined.
     */
    public function getClientIp(): ?string
    {
        $remoteAddr = $this->get('REMOTE_ADDR');
        if (in_array($remoteAddr, $this->trustedProxies, true)) {
            $ips = explode(',', $this->get('HTTP_X_FORWARDED_FOR'));
            $ip = trim($ips[0]);

            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }

        return $remoteAddr;
    }

    /**
     * Retrieves the protocol used to make the request ('http' or 'https').
     *
     * @return string The protocol used for the request.
     */
    public function getProtocol(): string
    {
        return (!empty($this->get('HTTPS')) && $this->get('HTTPS') !== 'off') ? 'https' : 'http';
    }

    /**
     * Retrieves the host name from the current request.
     *
     * @return ?string The host name.
     */
    public function getHost(): ?string
    {
        return $this->get('HTTP_HOST');
    }

    /**
     * Retrieves the server port from the current request.
     *
     * @return ?string The server port number.
     */
    public function getServerPort(): ?string
    {
        return $this->get('SERVER_PORT');
    }

    /**
     * Retrieves the User-Agent header from the current request.
     *
     * @return ?string The User-Agent string.
     */
    public function getUserAgent(): ?string
    {
        return $this->get('HTTP_USER_AGENT');
    }

    /**
     * Retrieves the Referer header from the current request.
     *
     * @return ?string The URL of the page that referred the user to the current page.
     */
    public function getReferer(): ?string
    {
        return $this->get('HTTP_REFERER');
    }

    /**
     * Retrieves the script name from the current request.
     *
     * @return ?string The name of the script which is currently executing.
     */
    public function getScriptName(): ?string
    {
        return $this->get('SCRIPT_NAME');
    }

    /**
     * Retrieves the server address from the current request.
     *
     * @return ?string The IP address of the server under which the current script is executing.
     */
    public function getServerAddress(): ?string
    {
        return $this->get('SERVER_ADDR');
    }

    /**
     * Provides a default set of HTTP headers.
     *
     * @return array An array of default HTTP headers.
     */
    public function getDefaultHeaders(): array
    {
        return [
            'Content-Type' => 'text/html',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ];
    }

    /**
     * Retrieves all HTTP headers from the current request, including default headers if they are not overridden.
     *
     * @return array An associative array of the HTTP headers in the current request.
     */
    public function getHeaders(): array
    {
        $headers = $this->getDefaultHeaders();
        foreach ($this->parameters as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $headerName = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($key, 5)))));
                $headers[$headerName] = $value;
            }
        }

        return $headers;
    }

    /**
     * Retrieves a specific HTTP header from the server variables.
     *
     * @param string $name The header name.
     * @return string|null The header value or null if not found.
     */
    public function getHeader(string $name): ?string
    {
        $key = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
        return $_SERVER[$key] ?? null;
    }
}

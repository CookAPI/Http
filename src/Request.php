<?php

declare(strict_types=1);

namespace Cook\Component\Http;

/**
 * Encapsulates all data related to an HTTP request, including query parameters, POST data,
 * cookies, uploaded files, and server/environment variables.
 */
class Request
{
    /**
     * @var Method To manage query string parameters.
     */
    public Method $query;

    /**
     * @var Method To manage POST parameters.
     */
    public Method $request;

    /**
     * @var Cookie To manage cookies.
     */
    public Cookie $cookie;

    /**
     * @var File To manage file uploads.
     */
    public File $file;

    /**
     * @var Server To manage server and execution environment information.
     */
    public Server $server;

    /**
     * Constructs a new Request object, initializing sub-objects for managing different types of request data.
     */
    public function __construct(
        array $requestParams = [],
        array $queryParams = [],
        array $cookieParams = [],
        array $fileParams = [],
        array $serverParams = []
    ) {
        $this->query = new Method($queryParams);
        $this->request = new Method($requestParams);
        $this->cookie = new Cookie($cookieParams);
        $this->file = new File($fileParams);
        $this->server = new Server($serverParams);
    }

    /**
     * Factory method to create a Request instance from global PHP variables.
     *
     * @return Request A new Request object populated with global PHP request variables.
     */
    public static function createFromGlobals(): Request
    {
        return new self($_POST, $_GET, $_COOKIE, $_FILES, $_SERVER);
    }

    /**
     * Retrieves all query parameters from the URL as an associative array.
     *
     * @return array An associative array of all query parameters.
     */
    public function getQuery(): array
    {
        return $this->query->all();
    }

    /**
     * Retrieves all POST parameters from the request body as an associative array.
     * This is typically used with form submissions sent with the "application/x-www-form-urlencoded"
     * or "multipart/form-data" content types.
     *
     * @return array An associative array of all POST parameters.
     */
    public function getPost(): array
    {
        return $this->request->all();
    }

    /**
     * Retrieves all cookies sent with the request as an associative array.
     *
     * @return array An associative array of all cookies.
     */
    public function getCookie(): array
    {
        return $this->cookie->all();
    }

    /**
     * Retrieves metadata for all files uploaded via the request as an associative array.
     * Each entry in the array contains file metadata such as name, type, size, tmp_name, and error.
     *
     * @return array An associative array of all files metadata.
     */
    public function getFile(): array
    {
        return $this->file->all();
    }

    /**
     * Retrieves all server and execution environment information as an associative array.
     * This typically includes data such as headers, paths, and script locations.
     *
     * @return array An associative array of all server parameters.
     */
    public function getServer(): array
    {
        return $this->server->all();
    }

    /**
     * Retrieves the Request URI, which is the URI that was given in order to access the current page.
     * This is often used for routing purposes.
     *
     * @return string The request URI.
     */
    public function getRequestUri(): string
    {
        return $this->server->getRequestUri();
    }
}

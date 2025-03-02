# HTTP Library

The HTTP Library is a versatile PHP toolkit designed to facilitate the handling of HTTP requests and responses
in web applications. It offers a clear and efficient way to manage request data, middleware operations, response
generation, and much more.

## Installation

To add the HTTP Library to your project, use Composer, the PHP package manager. First, ensure Composer is installed
in your project. If not, follow the installation instructions at [getcomposer.org](https://getcomposer.org/download/).
Then, run the following command in your project root:

```bash
composer require cook/http
```

This command will install the HTTP Library and its dependencies into your project, making it ready for use.

## Using Middleware

Middleware allows you to perform actions or checks on the HTTP request and response cycle. This feature is useful
for tasks like logging, authentication, or modifying the request/response objects.

### Example Middleware: LoggingMiddleware

```php
namespace Cook\Component\Http;

class LoggingMiddleware implements Middleware
{
    public function handle(Request $request, callable $next): Response
    {
        error_log("Logging Request: " . $request->getRequestUri());
        $response = $next($request);
        error_log("Logging Response: " . $response->getStatusCode());

        return $response;
    }
}
```

### Middleware Usage

```php
$request = Request::createFromGlobals();
$stack = new MiddlewareStack();
$stack->addMiddleware(new LoggingMiddleware());

$response = $stack->handle($request);
$response->send();
```

## File Upload Handling

Handling file uploads securely and efficiently is crucial in many web applications. The HTTP Library simplifies
this process.

### Handling a File Upload

```php
$request = Request::createFromGlobals();

// Assuming a form field named 'uploadedFile'
if ($request->file->has('uploadedFile')) {
    $file = $request->file->get('uploadedFile');
    $uploadPath = '/path/to/uploads';

    // Move the uploaded file
    if ($request->file->upload('uploadedFile', $file['name'], $uploadPath)) {
        echo "File uploaded successfully.";
    } else {
        echo "File upload failed.";
    }
}
```

## Contributing

We welcome contributions to the HTTP Library! Feel free to submit pull requests or issues through our GitHub
repository to suggest improvements or report bugs.

## License

The HTTP Library is open-source software licensed under the MIT license.

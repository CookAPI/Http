<?php

declare(strict_types=1);

namespace Cook\Component\Http;

/**
 * The Cookie class manages HTTP cookies securely.
 */
final class Cookie extends Parameter
{
    /**
     * Stores cookie metadata.
     * @var array
     */
    private static array $cookieMetadata = [];

    /**
     * Sets a secure cookie.
     *
     * @param string $key The cookie name.
     * @param mixed $value The cookie value (must be scalar).
     * @param int $expire Expiration time in seconds (0 = session).
     * @param string $path Path scope.
     * @param string $domain Domain scope.
     * @param bool $secure Secure flag (HTTPS only).
     * @param bool $httponly HTTP-only flag.
     * @param string $samesite SameSite policy ('Strict', 'Lax', 'None').
     * @return Parameter
     */
    public function set(
        string $key,
        mixed $value,
        int $expire = 0,
        string $path = '/',
        string $domain = '',
        bool $secure = true,
        bool $httponly = true,
        string $samesite = 'Strict'
    ): Parameter {
        if (!is_scalar($value)) {
            throw new \InvalidArgumentException("Cookie value must be a scalar value.");
        }

        $encodedValue = rawurlencode((string) $value);
        self::$cookieMetadata[$key] = [
            'value' => $encodedValue,
            'expires' => time() + $expire,
            'path' => $path,
            'domain' => $domain,
            'secure' => $secure,
            'httponly' => $httponly,
            'samesite' => $samesite
        ];

        setcookie($key, $encodedValue, [
            'expires' => self::$cookieMetadata[$key]['expires'],
            'path' => $path,
            'domain' => $domain,
            'secure' => $secure,
            'httponly' => $httponly,
            'samesite' => $samesite
        ]);

        return $this;
    }

    /**
     * Deletes a cookie.
     *
     * @param string $key Cookie name.
     * @return void
     */
    public function delete(string $key): void
    {
        if (isset(self::$cookieMetadata[$key])) {
            setcookie($key, '', [
                'expires' => time() - 3600,
                'path' => self::$cookieMetadata[$key]['path'] ?? '/',
                'domain' => self::$cookieMetadata[$key]['domain'] ?? '',
                'secure' => self::$cookieMetadata[$key]['secure'] ?? true,
                'httponly' => self::$cookieMetadata[$key]['httponly'] ?? true,
                'samesite' => self::$cookieMetadata[$key]['samesite'] ?? 'Strict'
            ]);
            unset(self::$cookieMetadata[$key]);
        }
    }
}

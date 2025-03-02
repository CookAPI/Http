<?php

namespace Cook\Component\Http;

/**
 * Manages uploaded files securely and efficiently.
 */
final class File extends Parameter
{
    /** @var array<string, mixed> Stores metadata about uploaded files. */
    private static array $fileMetadata = [];

    /** @var array<int, string> Allowed file extensions. */
    private const ALLOWED_EXTENSIONS = ['jpg', 'png', 'gif', 'pdf', 'txt', 'zip'];

    /** @var int Maximum file size in bytes (5MB). */
    private const MAX_FILE_SIZE = 5 * 1024 * 1024;

    /**
     * Retrieves a file's metadata from $_FILES.
     *
     * @param string $key File input name.
     * @return ?array<string, mixed> Metadata or null if not found.
     */
    public function getMetadata(string $key): ?array
    {
        return self::$fileMetadata[$key] ?? null;
    }

    /**
     * Validates the uploaded file against allowed types and size.
     *
     * @param string $key File key.
     * @return bool True if valid, false otherwise.
     */
    public function isValid(string $key): bool
    {
        if (!isset($_FILES[$key])) {
            return false;
        }

        $file = $_FILES[$key];
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        return (
            in_array($extension, self::ALLOWED_EXTENSIONS, true) &&
            $file['size'] <= self::MAX_FILE_SIZE &&
            $file['error'] === UPLOAD_ERR_OK
        );
    }

    /**
     * Moves an uploaded file to a new location securely.
     *
     * @param string $key File input name.
     * @param string $targetPath Destination directory.
     * @return bool True on success, false on failure.
     * @throws \RuntimeException If the directory is not writable.
     */
    public function move(string $key, string $targetPath): bool
    {
        if (!$this->isValid($key)) {
            return false;
        }

        $file = $_FILES[$key];
        $targetPath = rtrim($targetPath, '/') . '/';
        $filePath = $targetPath . basename($file['name']);

        if (!is_dir($targetPath)) {
            if (!mkdir($targetPath, 0777, true) || !is_writable($targetPath)) {
                throw new \RuntimeException("Cannot create or write to directory: $targetPath");
            }
        }

        if (!file_exists($file['tmp_name'])) {
            throw new \RuntimeException("Temporary file does not exist: " . $file['tmp_name']);
        }

        if (!is_writable($targetPath)) {
            throw new \RuntimeException("Target directory is not writable: $targetPath");
        }

        if (!rename($file['tmp_name'], $filePath)) {
            throw new \RuntimeException("Failed to move uploaded file to: $filePath");
        }

        return true;
    }
}

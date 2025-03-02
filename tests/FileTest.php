<?php

declare(strict_types=1);

use Cook\Component\Http\File;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the File class.
 */
final class FileTest extends TestCase
{
    private File $file;

    protected function setUp(): void
    {
        $this->file = new File();
        $_FILES = []; // Nettoyage avant chaque test
    }

    /**
     * Test file validation with valid file metadata.
     */
    public function testValidFile(): void
    {
        $_FILES['test_file'] = [
            'name' => 'example.jpg',
            'type' => 'image/jpeg',
            'size' => 1024,
            'tmp_name' => '/tmp/php12345',
            'error' => UPLOAD_ERR_OK
        ];

        $this->assertTrue($this->file->isValid('test_file'));
    }

    /**
     * Test file validation failure with a missing file.
     */
    public function testInvalidFileMissing(): void
    {
        $this->assertFalse($this->file->isValid('non_existent_file'));
    }

    /**
     * Test file validation failure due to excessive size.
     */
    public function testInvalidFileSize(): void
    {
        $_FILES['test_large_file'] = [
            'name' => 'large_file.jpg',
            'type' => 'image/jpeg',
            'size' => 6 * 1024 * 1024, // 6MB (exceeds max size)
            'tmp_name' => '/tmp/php12346',
            'error' => UPLOAD_ERR_OK
        ];

        $this->assertFalse($this->file->isValid('test_large_file'));
    }

    /**
     * Test file validation failure due to invalid extension.
     */
    public function testInvalidFileExtension(): void
    {
        $_FILES['test_invalid_ext'] = [
            'name' => 'file.exe',
            'type' => 'application/x-msdownload',
            'size' => 512,
            'tmp_name' => '/tmp/php12347',
            'error' => UPLOAD_ERR_OK
        ];

        $this->assertFalse($this->file->isValid('test_invalid_ext'));
    }

    /**
     * Test file move success.
     */
    public function testMoveFileSuccess(): void
    {
        $_FILES['test_file'] = [
            'name' => 'example.jpg',
            'type' => 'image/jpeg',
            'size' => 1024,
            'tmp_name' => sys_get_temp_dir() . '/php12345',
            'error' => UPLOAD_ERR_OK
        ];

        // Simuler un fichier temporaire
        file_put_contents($_FILES['test_file']['tmp_name'], 'test content');

        $targetPath = sys_get_temp_dir() . '/uploads';
        @mkdir($targetPath); // Créer le répertoire s'il n'existe pas

        $this->assertTrue($this->file->move('test_file', $targetPath));

        // Vérifier que le fichier a bien été déplacé
        $this->assertFileExists($targetPath . '/example.jpg');

        // Nettoyage après test
        unlink($targetPath . '/example.jpg');
    }

    /**
     * Test file move failure due to non-writable directory.
     */
    public function testMoveFileFailure(): void
    {
        $_FILES['test_file'] = [
            'name' => 'example.jpg',
            'type' => 'image/jpeg',
            'size' => 1024,
            'tmp_name' => sys_get_temp_dir() . '/php12345',
            'error' => UPLOAD_ERR_OK
        ];

        // Simuler un fichier temporaire
        file_put_contents($_FILES['test_file']['tmp_name'], 'test content');

        // Créer un dossier temporaire et le verrouiller
        $targetPath = sys_get_temp_dir() . '/locked_directory';
        mkdir($targetPath, 0444); // Lecture seule

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Target directory is not writable: $targetPath");

        $this->file->move('test_file', $targetPath);

        // Nettoyer après le test
        chmod($targetPath, 0755);
        rmdir($targetPath);
    }
}

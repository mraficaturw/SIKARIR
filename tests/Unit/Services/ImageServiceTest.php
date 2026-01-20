<?php

namespace Tests\Unit\Services;

use App\Services\ImageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageServiceTest extends TestCase
{
    // =========================================================================
    // Test: convertToWebp
    // =========================================================================

    public function test_convert_to_webp_returns_string(): void
    {
        $file = UploadedFile::fake()->image('test.jpg', 100, 100);

        $result = ImageService::convertToWebp($file);

        $this->assertIsString($result);
        $this->assertNotEmpty($result);
    }

    public function test_convert_to_webp_with_custom_quality(): void
    {
        $file = UploadedFile::fake()->image('test.jpg', 100, 100);

        $highQuality = ImageService::convertToWebp($file, 100);
        $lowQuality = ImageService::convertToWebp($file, 10);

        // Both should be strings
        $this->assertIsString($highQuality);
        $this->assertIsString($lowQuality);
    }

    // =========================================================================
    // Test: convertAndUpload
    // =========================================================================

    public function test_convert_and_upload_stores_file(): void
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->image('test.jpg', 100, 100);

        $path = ImageService::convertAndUpload($file, 'local', 'avatars', 80);

        // Path should contain directory and .webp extension
        $this->assertStringContainsString('avatars/', $path);
        $this->assertStringEndsWith('.webp', $path);

        // File should exist in storage
        Storage::disk('local')->assertExists($path);
    }

    public function test_convert_and_upload_generates_unique_filename(): void
    {
        Storage::fake('local');

        $file1 = UploadedFile::fake()->image('test.jpg', 100, 100);
        $file2 = UploadedFile::fake()->image('test.jpg', 100, 100);

        // Wait a second to ensure different timestamps
        $path1 = ImageService::convertAndUpload($file1, 'local', 'avatars', 80);
        sleep(1);
        $path2 = ImageService::convertAndUpload($file2, 'local', 'avatars', 80);

        // Paths should be different due to timestamp
        $this->assertNotEquals($path1, $path2);
    }

    public function test_convert_and_upload_preserves_original_filename(): void
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->image('my_profile_picture.jpg', 100, 100);

        $path = ImageService::convertAndUpload($file, 'local', 'avatars', 80);

        // Path should contain original filename (without extension)
        $this->assertStringContainsString('my_profile_picture_', $path);
    }
}

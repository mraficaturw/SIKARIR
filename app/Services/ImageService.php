<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ImageService
{
    /**
     * Convert image to WebP format and return the file content as string
     */
    public static function convertToWebp(UploadedFile $file, int $quality = 80): string
    {
        $image = Image::read($file->getRealPath());

        return $image->toWebp($quality)->toString();
    }

    /**
     * Convert image to WebP and upload to specified disk
     */
    public static function convertAndUpload(
        UploadedFile $file,
        string $disk,
        string $directory,
        int $quality = 80
    ): string {
        $image = Image::read($file->getRealPath());

        // Generate unique filename with .webp extension
        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $filename = $filename . '_' . time() . '.webp';
        $path = $directory . '/' . $filename;

        // Convert to WebP
        $webpContent = $image->toWebp($quality)->toString();

        // Upload to storage
        Storage::disk($disk)->put($path, $webpContent, 'public');

        return $path;
    }
}

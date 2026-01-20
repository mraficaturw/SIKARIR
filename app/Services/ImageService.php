<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

/**
 * ============================================================================
 * IMAGE SERVICE
 * ============================================================================
 * Service class untuk memproses dan mengoptimasi gambar.
 * Menggunakan library Intervention Image untuk manipulasi gambar.
 * 
 * Fitur utama:
 * - Konversi gambar ke format WebP (ukuran lebih kecil, kualitas tetap)
 * - Upload gambar ke berbagai disk storage (lokal, Supabase, S3, dll)
 * - Generate nama file unik dengan timestamp
 * 
 * Keuntungan format WebP:
 * - Ukuran file 25-35% lebih kecil dari JPEG
 * - Mendukung transparansi (seperti PNG)
 * - Didukung oleh semua browser modern
 * ============================================================================
 */
class ImageService
{
    /**
     * -------------------------------------------------------------------------
     * Konversi Gambar ke Format WebP
     * -------------------------------------------------------------------------
     * Method ini mengkonversi gambar yang diupload ke format WebP
     * dan mengembalikan konten gambar sebagai string.
     * 
     * Berguna jika ingin memproses gambar tanpa langsung menyimpan.
     * 
     * Contoh penggunaan:
     * $webpContent = ImageService::convertToWebp($request->file('image'), 80);
     * // Atau simpan manual ke file
     * file_put_contents('output.webp', $webpContent);
     * 
     * @param UploadedFile $file File gambar yang diupload dari form
     * @param int $quality Kualitas output (1-100, default: 80)
     *                     80 adalah sweet spot antara kualitas dan ukuran
     * @return string Konten gambar WebP dalam bentuk binary string
     */
    public static function convertToWebp(UploadedFile $file, int $quality = 80): string
    {
        // Baca gambar menggunakan Intervention Image
        // getRealPath() mendapatkan path temporary file yang diupload
        $image = Image::read($file->getRealPath());

        // Konversi ke WebP dengan kualitas yang ditentukan
        // toString() mengembalikan binary content, bukan menyimpan ke file
        return $image->toWebp($quality)->toString();
    }

    /**
     * -------------------------------------------------------------------------
     * Konversi dan Upload Gambar
     * -------------------------------------------------------------------------
     * Method all-in-one yang mengkonversi gambar ke WebP dan langsung
     * mengupload ke disk storage yang ditentukan.
     * 
     * Proses:
     * 1. Baca dan konversi gambar ke WebP
     * 2. Generate nama file unik (original_name_timestamp.webp)
     * 3. Upload ke disk storage (Supabase, S3, lokal, dll)
     * 4. Return path file yang tersimpan
     * 
     * Contoh penggunaan:
     * // Upload avatar ke Supabase
     * $path = ImageService::convertAndUpload(
     *     $request->file('avatar'),
     *     'supabase-avatar',  // Nama disk di config/filesystems.php
     *     'avatars',          // Folder di dalam bucket
     *     80                  // Kualitas gambar
     * );
     * // Result: "avatars/john_1705123456.webp"
     * 
     * // Upload logo perusahaan
     * $path = ImageService::convertAndUpload(
     *     $request->file('logo'),
     *     'supabase',
     *     'logos',
     *     80
     * );
     * 
     * @param UploadedFile $file File gambar yang diupload dari form
     * @param string $disk Nama disk storage dari config/filesystems.php
     *                     Contoh: 'local', 'public', 'supabase', 'supabase-avatar'
     * @param string $directory Folder/directory di dalam disk
     *                          Contoh: 'avatars', 'logos', 'images'
     * @param int $quality Kualitas output (1-100, default: 80)
     * @return string Path lengkap file yang tersimpan (relatif terhadap disk)
     */
    public static function convertAndUpload(
        UploadedFile $file,
        string $disk,
        string $directory,
        int $quality = 80
    ): string {
        // -----------------------------------------------------------------
        // LANGKAH 1: Baca Gambar
        // -----------------------------------------------------------------
        // Gunakan Intervention Image untuk membaca file
        $image = Image::read($file->getRealPath());

        // -----------------------------------------------------------------
        // LANGKAH 2: Generate Nama File Unik
        // -----------------------------------------------------------------
        // Ambil nama file asli tanpa ekstensi
        // Contoh: "profile_picture.jpg" → "profile_picture"
        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        // Tambahkan timestamp untuk menghindari duplikasi nama
        // Contoh: "profile_picture_1705123456.webp"
        $filename = $filename . '_' . time() . '.webp';

        // Gabungkan dengan directory untuk path lengkap
        // Contoh: "avatars/profile_picture_1705123456.webp"
        $path = $directory . '/' . $filename;

        // -----------------------------------------------------------------
        // LANGKAH 3: Konversi ke WebP
        // -----------------------------------------------------------------
        // Konversi gambar ke format WebP dengan kualitas yang ditentukan
        $webpContent = $image->toWebp($quality)->toString();

        // -----------------------------------------------------------------
        // LANGKAH 4: Upload ke Storage
        // -----------------------------------------------------------------
        // Simpan ke disk yang ditentukan dengan visibility 'public'
        // 'public' artinya file bisa diakses melalui URL
        Storage::disk($disk)->put($path, $webpContent, 'public');

        // -----------------------------------------------------------------
        // LANGKAH 5: Return Path
        // -----------------------------------------------------------------
        // Path ini yang akan disimpan ke database
        // Untuk mendapatkan URL lengkap, gunakan accessor di model
        return $path;
    }
}

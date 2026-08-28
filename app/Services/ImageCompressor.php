<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageCompressor
{
    /**
     * Compress, resize, and store an uploaded image.
     *
     * @param UploadedFile $file The uploaded image file
     * @param string $folder Storage directory inside public disk (e.g., 'profiles', 'media/photos')
     * @param int $maxWidth Maximum allowable width in pixels (default: 1920)
     * @param int $maxHeight Maximum allowable height in pixels (default: 1920)
     * @param int $quality Compression quality 1-100 (default: 82 for optimal quality/size balance)
     * @param string $format Target format ('webp' or 'jpg', default: 'webp')
     * @return string Relative path stored on the public disk
     */
    public static function compressAndStore(
        UploadedFile $file,
        string $folder = 'photos',
        int $maxWidth = 1920,
        int $maxHeight = 1920,
        int $quality = 82,
        string $format = 'webp'
    ): string {
        @ini_set('memory_limit', '512M');
        $realPath = $file->getRealPath();

        if (!$realPath || !file_exists($realPath)) {
            return $file->store($folder, 'public');
        }

        $mime = strtolower($file->getMimeType() ?? '');

        // Create GD image resource based on file type
        $sourceImage = match ($mime) {
            'image/jpeg', 'image/jpg', 'image/pjpeg' => function_exists('imagecreatefromjpeg') ? @imagecreatefromjpeg($realPath) : null,
            'image/png', 'image/x-png' => function_exists('imagecreatefrompng') ? @imagecreatefrompng($realPath) : null,
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($realPath) : null,
            'image/gif' => function_exists('imagecreatefromgif') ? @imagecreatefromgif($realPath) : null,
            'image/bmp', 'image/x-ms-bmp' => function_exists('imagecreatefrombmp') ? @imagecreatefrombmp($realPath) : null,
            default => null,
        };

        // Fallback: If GD fails to read, fallback to standard store
        if (!$sourceImage) {
            return $file->store($folder, 'public');
        }

        // Auto-orient mobile camera photos using EXIF metadata if available
        if (function_exists('exif_read_data') && in_array($mime, ['image/jpeg', 'image/jpg'])) {
            try {
                $exif = @exif_read_data($realPath);
                if (!empty($exif['Orientation'])) {
                    switch ($exif['Orientation']) {
                        case 3:
                            $sourceImage = imagerotate($sourceImage, 180, 0);
                            break;
                        case 6:
                            $sourceImage = imagerotate($sourceImage, -90, 0);
                            break;
                        case 8:
                            $sourceImage = imagerotate($sourceImage, 90, 0);
                            break;
                    }
                }
            } catch (\Throwable $e) {
                // Ignore EXIF read errors
            }
        }

        $origWidth = imagesx($sourceImage);
        $origHeight = imagesy($sourceImage);

        // Calculate auto-scaled dimensions preserving aspect ratio
        if ($origWidth > $maxWidth || $origHeight > $maxHeight) {
            $ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight);
            $newWidth = (int) round($origWidth * $ratio);
            $newHeight = (int) round($origHeight * $ratio);
        } else {
            $newWidth = $origWidth;
            $newHeight = $origHeight;
        }

        // Create canvas for high-quality resampling
        $canvas = imagecreatetruecolor($newWidth, $newHeight);

        // Preserve alpha transparency for PNG / WebP
        if (in_array($format, ['webp', 'png'])) {
            imagealphablending($canvas, false);
            imagesavealpha($canvas, true);
            $transparent = imagecolorallocatealpha($canvas, 255, 255, 255, 127);
            imagefilledrectangle($canvas, 0, 0, $newWidth, $newHeight, $transparent);
        } else {
            // Fill background with white for JPEG
            $white = imagecolorallocate($canvas, 255, 255, 255);
            imagefilledrectangle($canvas, 0, 0, $newWidth, $newHeight, $white);
        }

        // High quality bicubic resampling
        imagecopyresampled(
            $canvas,
            $sourceImage,
            0, 0, 0, 0,
            $newWidth, $newHeight,
            $origWidth, $origHeight
        );

        // Determine destination file name and path
        $extension = ($format === 'webp' && function_exists('imagewebp')) ? 'webp' : 'jpg';
        $filename = Str::random(40) . '.' . $extension;
        
        // Ensure storage directory exists on public disk
        Storage::disk('public')->makeDirectory($folder);
        $absolutePath = Storage::disk('public')->path($folder . '/' . $filename);

        // Output compressed image to file
        if ($extension === 'webp' && function_exists('imagewebp')) {
            imagewebp($canvas, $absolutePath, $quality);
        } else {
            imagejpeg($canvas, $absolutePath, $quality);
        }

        // Free memory
        imagedestroy($sourceImage);
        imagedestroy($canvas);

        return $folder . '/' . $filename;
    }
}

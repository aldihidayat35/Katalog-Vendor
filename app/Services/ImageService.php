<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Store and optimize an uploaded image as WebP format with proportional resizing.
     *
     * @param UploadedFile $file The uploaded file
     * @param string $directory Subdirectory inside storage/app/public (e.g. 'products', 'banners')
     * @param int $maxDimension Maximum width or height in pixels
     * @param int $quality WebP compression quality (0-100)
     * @return string Stored relative path (e.g. 'products/abc123xyz.webp')
     */
    public static function storeAsWebp(
        UploadedFile $file,
        string $directory = 'products',
        int $maxDimension = 1200,
        int $quality = 82
    ): string {
        $mime = $file->getMimeType();
        $extension = strtolower($file->getClientOriginalExtension());

        // For SVG or non-raster formats, store as-is without GD conversion
        if ($extension === 'svg' || str_contains($mime, 'svg')) {
            return $file->store($directory, 'public');
        }

        // If GD extension or imagewebp is unavailable, fallback to standard store
        if (! extension_loaded('gd') || ! function_exists('imagewebp')) {
            Log::warning('GD extension or imagewebp not available, saving original file.');
            return $file->store($directory, 'public');
        }

        try {
            $sourceImage = self::createImageFromSource($file->getRealPath(), $mime, $extension);

            if (! $sourceImage) {
                return $file->store($directory, 'public');
            }

            // Correct EXIF orientation for JPEG photos (common in smartphone camera uploads)
            $sourceImage = self::correctExifOrientation($file->getRealPath(), $sourceImage, $extension);

            $origWidth = imagesx($sourceImage);
            $origHeight = imagesy($sourceImage);

            // Calculate new dimensions preserving aspect ratio (no upscaling)
            [$newWidth, $newHeight] = self::calculateDimensions($origWidth, $origHeight, $maxDimension);

            // Create target truecolor canvas
            $targetImage = imagecreatetruecolor($newWidth, $newHeight);

            // Preserve alpha transparency for PNG / WebP
            imagealphablending($targetImage, false);
            imagesavealpha($targetImage, true);
            $transparent = imagecolorallocatealpha($targetImage, 0, 0, 0, 127);
            imagefilledrectangle($targetImage, 0, 0, $newWidth, $newHeight, $transparent);

            // Resample source into target
            imagecopyresampled(
                $targetImage,
                $sourceImage,
                0, 0, 0, 0,
                $newWidth,
                $newHeight,
                $origWidth,
                $origHeight
            );

            // Capture WebP buffer
            ob_start();
            imagewebp($targetImage, null, $quality);
            $webpData = ob_get_clean();

            // Destroy GD image resources to free memory immediately
            imagedestroy($sourceImage);
            imagedestroy($targetImage);

            // Generate unique filename and store on public disk
            $filename = Str::random(40) . '.webp';
            $targetPath = trim($directory, '/') . '/' . $filename;

            Storage::disk('public')->put($targetPath, $webpData);

            return $targetPath;
        } catch (\Throwable $e) {
            Log::error('ImageService WebP conversion failed: ' . $e->getMessage(), [
                'exception' => $e,
                'file' => $file->getClientOriginalName(),
            ]);

            // Graceful fallback to raw file upload if conversion encounters error
            return $file->store($directory, 'public');
        }
    }

    /**
     * Create GD image resource based on file type.
     */
    protected static function createImageFromSource(string $filePath, string $mime, string $extension)
    {
        if (str_contains($mime, 'jpeg') || in_array($extension, ['jpg', 'jpeg'])) {
            return @imagecreatefromjpeg($filePath);
        }

        if (str_contains($mime, 'png') || $extension === 'png') {
            return @imagecreatefrompng($filePath);
        }

        if (str_contains($mime, 'webp') || $extension === 'webp') {
            return @imagecreatefromwebp($filePath);
        }

        if (str_contains($mime, 'gif') || $extension === 'gif') {
            return @imagecreatefromgif($filePath);
        }

        return false;
    }

    /**
     * Correct smartphone EXIF rotation for JPEGs.
     */
    protected static function correctExifOrientation(string $filePath, $image, string $extension)
    {
        if (! function_exists('exif_read_data') || ! in_array($extension, ['jpg', 'jpeg'])) {
            return $image;
        }

        $exif = @exif_read_data($filePath);
        if (empty($exif['Orientation'])) {
            return $image;
        }

        switch ($exif['Orientation']) {
            case 3:
                $rotated = imagerotate($image, 180, 0);
                imagedestroy($image);
                return $rotated;
            case 6:
                $rotated = imagerotate($image, -90, 0);
                imagedestroy($image);
                return $rotated;
            case 8:
                $rotated = imagerotate($image, 90, 0);
                imagedestroy($image);
                return $rotated;
            default:
                return $image;
        }
    }

    /**
     * Calculate proportional dimensions not exceeding max dimension.
     */
    protected static function calculateDimensions(int $width, int $height, int $maxDimension): array
    {
        if ($width <= $maxDimension && $height <= $maxDimension) {
            return [$width, $height];
        }

        if ($width >= $height) {
            $newWidth = $maxDimension;
            $newHeight = (int) round($height * ($maxDimension / $width));
        } else {
            $newHeight = $maxDimension;
            $newWidth = (int) round($width * ($maxDimension / $height));
        }

        return [max(1, $newWidth), max(1, $newHeight)];
    }
}

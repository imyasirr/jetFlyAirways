<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

final class PublicImageStorage
{
    public const DISK = 'public';

    public static function url(?string $storedPath): ?string
    {
        if ($storedPath === null || $storedPath === '') {
            return null;
        }
        if (preg_match('#^https?://#i', $storedPath)) {
            return $storedPath;
        }

        return asset('storage/'.ltrim($storedPath, '/'));
    }

    public static function storeUpload(?UploadedFile $file, string $directory, ?string $deleteRelativePath = null): ?string
    {
        if ($file === null || ! $file->isValid()) {
            return null;
        }

        self::deleteIfExists($deleteRelativePath);

        return $file->store(trim($directory, '/'), self::DISK);
    }

    public static function deleteIfExists(?string $relativePath): void
    {
        if ($relativePath === null || $relativePath === '') {
            return;
        }
        if (preg_match('#^https?://#i', $relativePath)) {
            return;
        }

        Storage::disk(self::DISK)->delete($relativePath);
    }
}

<?php

namespace App\Services;

use App\Models\UploadedFile;
use Illuminate\Http\UploadedFile as HttpUploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class FileService
{
    private const MAX_FILE_SIZE = 536870912; // 512MB
    private const MIN_RETENTION_DAYS = 30;
    private const MAX_RETENTION_DAYS = 365;

    public function uploadFile(HttpUploadedFile $file): UploadedFile
    {
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            throw new \Exception('File too large');
        }

        $fileHash = $this->generateFileHash();
        $deleteToken = Str::random(32);
        
        // Исправление: используем только хеш без расширения для универсальности
        $fileName = $fileHash;
        $filePath = 'uploads/' . $fileName;

        // Сохраняем файл с оригинальным содержимым
        Storage::put($filePath, file_get_contents($file->getRealPath()));

        // Вычисляем время жизни файла
        $expiresAt = $this->calculateRetention($file->getSize());

        return UploadedFile::create([
            'file_hash' => $fileHash,
            'delete_token' => $deleteToken,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType() ?: 'application/octet-stream',
            'file_size' => $file->getSize(),
            'file_path' => $filePath,
            'expires_at' => $expiresAt
        ]);
    }

    public function deleteFile(string $deleteToken): bool
    {
        $uploadedFile = UploadedFile::where('delete_token', $deleteToken)->first();
        
        if (!$uploadedFile) {
            return false;
        }

        if (Storage::exists($uploadedFile->file_path)) {
            Storage::delete($uploadedFile->file_path);
        }
        $uploadedFile->delete();

        return true;
    }

    public function getFile(string $fileHash): ?UploadedFile
    {
        $file = UploadedFile::where('file_hash', $fileHash)->first();
        
        if (!$file || $file->isExpired()) {
            // Удаляем просроченный файл
            if ($file && $file->isExpired()) {
                if (Storage::exists($file->file_path)) {
                    Storage::delete($file->file_path);
                }
                $file->delete();
            }
            return null;
        }

        return $file;
    }

    public function cleanupExpiredFiles(): int
    {
        $expiredFiles = UploadedFile::where('expires_at', '<', now())->get();
        $count = 0;

        foreach ($expiredFiles as $file) {
            if (Storage::exists($file->file_path)) {
                Storage::delete($file->file_path);
            }
            $file->delete();
            $count++;
        }

        return $count;
    }

    private function generateFileHash(): string
    {
        do {
            $hash = Str::random(8);
        } while (UploadedFile::where('file_hash', $hash)->exists());

        return $hash;
    }

    private function calculateRetention(int $fileSize): Carbon
    {
        $maxSize = self::MAX_FILE_SIZE;
        $minAge = self::MIN_RETENTION_DAYS;
        $maxAge = self::MAX_RETENTION_DAYS;

        if ($fileSize >= $maxSize) {
            $days = $minAge;
        } else {
            $ratio = $fileSize / $maxSize;
            $days = $minAge + ($maxAge - $minAge) * pow((1 - $ratio), 3);
        }

        return now()->addDays((int)$days);
    }
}
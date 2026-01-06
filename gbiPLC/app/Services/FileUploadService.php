<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    /**
     * Upload file to storage
     */
    public function uploadFile(UploadedFile $file, string $directory, ?string $name = null): string
    {
        if (!$name) {
            return $file->store($directory, 'public');
        }

        $cleanName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $name);
        $extension = $file->getClientOriginalExtension();
        $filename = $cleanName . '.' . $extension;

        return $file->storeAs($directory, $filename, 'public');
    }

    /**
     * Delete file from storage
     */
    public function deleteFile(string $filePath): bool
    {
        if (Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->delete($filePath);
        }

        return false;
    }

    /**
     * Replace existing file with new one
     */
    public function replaceFile(UploadedFile $newFile, string $directory, ?string $name = null, ?string $oldFilePath = null): string
    {
        // Delete old file if exists
        if ($oldFilePath) {
            $this->deleteFile($oldFilePath);
        }

        // Upload new file
        return $this->uploadFile($newFile, $directory, $name);
    }
}

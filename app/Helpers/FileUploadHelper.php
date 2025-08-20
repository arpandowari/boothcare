<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUploadHelper
{
    /**
     * Upload a file and return the path
     */
    public static function uploadFile(UploadedFile $file, string $directory = 'uploads'): string
    {
        // Ensure directory exists
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }
        
        // Generate unique filename
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '_' . uniqid() . '.' . $extension;
        
        // Store file with custom name
        $path = $file->storeAs($directory, $filename, 'public');
        
        // Log successful upload for debugging
        \Log::info("File uploaded successfully", [
            'original_name' => $file->getClientOriginalName(),
            'stored_path' => $path,
            'directory' => $directory,
            'filename' => $filename,
            'size' => $file->getSize()
        ]);
        
        return $path;
    }

    /**
     * Delete a file from storage
     */
    public static function deleteFile(?string $filePath): bool
    {
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->delete($filePath);
        }
        return false;
    }

    /**
     * Get the full URL for a stored file
     */
    public static function getFileUrl(?string $filePath): ?string
    {
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            return Storage::url($filePath);
        }
        return null;
    }

    /**
     * Handle multiple file uploads for a model
     */
    public static function handleFileUploads(array $files, array $fileFields, $model = null): array
    {
        $uploadedFiles = [];

        foreach ($fileFields as $field) {
            if (isset($files[$field]) && $files[$field] instanceof UploadedFile) {
                try {
                    // Validate file before upload
                    $allowedMimes = $field === 'profile_photo' ? ['jpeg', 'png', 'jpg'] : ['jpeg', 'png', 'jpg', 'pdf'];
                    
                    if (!self::validateFile($files[$field], $allowedMimes)) {
                        continue; // Skip invalid files
                    }

                    // Delete old file if model exists and has the field
                    if ($model && $model->$field) {
                        self::deleteFile($model->$field);
                    }

                    // Upload new file
                    $directory = self::getDirectoryForField($field);
                    $uploadedFiles[$field] = self::uploadFile($files[$field], $directory);
                } catch (\Exception $e) {
                    // Log error but continue with other files
                    \Log::error("File upload failed for field {$field}: " . $e->getMessage());
                }
            }
        }

        return $uploadedFiles;
    }

    /**
     * Get appropriate directory based on field name
     */
    private static function getDirectoryForField(string $field): string
    {
        return match($field) {
            'profile_photo' => 'profile_photos',
            'aadhar_photo' => 'documents/aadhar',
            'pan_photo' => 'documents/pan',
            'voter_id_photo' => 'documents/voter_id',
            'ration_card_photo' => 'documents/ration_card',
            'problem_photo' => 'problem_photos',
            default => 'uploads'
        };
    }

    /**
     * Validate file upload
     */
    public static function validateFile(UploadedFile $file, array $allowedMimes = ['jpeg', 'png', 'jpg'], int $maxSize = 2048): bool
    {
        // Check file size (in KB)
        if ($file->getSize() > $maxSize * 1024) {
            return false;
        }

        // Check mime type
        $extension = $file->getClientOriginalExtension();
        return in_array(strtolower($extension), $allowedMimes);
    }

    /**
     * Get file size in human readable format
     */
    public static function getFileSize(?string $filePath): ?string
    {
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            $bytes = Storage::disk('public')->size($filePath);
            $units = ['B', 'KB', 'MB', 'GB'];
            
            for ($i = 0; $bytes > 1024; $i++) {
                $bytes /= 1024;
            }
            
            return round($bytes, 2) . ' ' . $units[$i];
        }
        return null;
    }
}
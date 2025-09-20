<?php

namespace App\Services;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService
{
    private const MAX_FILE_SIZE = 10 * 1024 * 1024; // 10MB
    private const MAX_IMAGE_SIZE = 5 * 1024 * 1024; // 5MB

    private readonly string $disk;

    public function __construct()
    {
        $this->disk = config('filesystems.default', 'public');
    }

    /**
     * Upload a file to the configured storage disk
     *
     * @param UploadedFile $file
     * @param string $path
     * @param array $options
     * @return array{path: string, url: string, size: int, mime_type: string}
     * @throws Exception
     */
    public function upload(UploadedFile $file, string $path, array $options = []): array
    {
        try {
            $this->validateFile($file, $options['max_size'] ?? self::MAX_FILE_SIZE);

            // Ensure path is clean and properly formatted
            $path = $this->sanitizePath($path);
            $filename = $this->generateFilename($file);
            $fullPath = $path . '/' . $filename;

            Log::info('Starting file upload', [
                'disk' => $this->disk,
                'path' => $fullPath,
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType()
            ]);

            // Store the file
            $storedPath = Storage::disk($this->disk)->putFileAs($path, $file, $filename);

            if (!$storedPath) {
                throw new Exception('Failed to store file');
            }

            // Get file URL
            $url = $this->getUrl($storedPath);

            $result = [
                'path' => $storedPath,
                'url' => $url,
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType()
            ];

            Log::info('File upload completed', $result);

            return $result;

        } catch (Exception $e) {
            Log::error('File upload failed', [
                'error' => $e->getMessage(),
                'path' => $path ?? null,
                'disk' => $this->disk
            ]);
            throw $e;
        }
    }

    /**
     * Upload an image for the visual builder
     *
     * @param UploadedFile $file
     * @param string $context
     * @return array{path: string, url: string, size: int, mime_type: string}
     * @throws Exception
     */
    public function uploadBuilderImage(UploadedFile $file, string $context = 'builder'): array
    {
        $path = "images/{$context}/" . date('Y/m');
        return $this->upload($file, $path, ['max_size' => self::MAX_IMAGE_SIZE]);
    }

    /**
     * Get a URL for a file
     *
     * @param string $path
     * @return string
     */
    public function getUrl(string $path): string
    {
        return Storage::disk($this->disk)->url($path);
    }

    /**
     * Delete a file
     *
     * @param string $path
     * @return bool
     * @throws Exception
     */
    public function delete(string $path): bool
    {
        try {
            $deleted = Storage::disk($this->disk)->delete($path);

            Log::info('File deleted', [
                'path' => $path,
                'disk' => $this->disk,
                'deleted' => $deleted
            ]);

            return $deleted;

        } catch (Exception $e) {
            Log::error('File deletion failed', [
                'path' => $path,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Check if a file exists
     *
     * @param string $path
     * @return bool
     */
    public function exists(string $path): bool
    {
        return Storage::disk($this->disk)->exists($path);
    }

    /**
     * Get all images in a directory
     *
     * @param string $directory
     * @return array
     */
    public function getImages(string $directory = 'images/builder'): array
    {
        try {
            $files = Storage::disk($this->disk)->files($directory);
            $images = [];

            foreach ($files as $file) {
                $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $images[] = [
                        'path' => $file,
                        'url' => $this->getUrl($file),
                        'name' => basename($file),
                        'size' => Storage::disk($this->disk)->size($file),
                        'mime_type' => Storage::disk($this->disk)->mimeType($file)
                    ];
                }
            }

            return $images;

        } catch (Exception $e) {
            Log::error('Failed to get images', [
                'directory' => $directory,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Validate file upload
     *
     * @param UploadedFile $file
     * @param int $maxSize
     * @return void
     * @throws Exception
     */
    private function validateFile(UploadedFile $file, int $maxSize): void
    {
        if (!$file || !$file->isValid()) {
            throw new Exception('Invalid file upload');
        }

        if ($file->getSize() > $maxSize) {
            throw new Exception("File size exceeds maximum limit of " . ($maxSize / 1024 / 1024) . "MB");
        }

        $allowedTypes = [
            'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg', 'webp',
            'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx',
            'txt', 'csv', 'zip', 'rar', '7z'
        ];

        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $allowedTypes)) {
            throw new Exception('File type not allowed. Allowed types are: ' . implode(', ', $allowedTypes));
        }
    }

    /**
     * Check if file is an image
     *
     * @param UploadedFile $file
     * @return bool
     */
    private function isImage(UploadedFile $file): bool
    {
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $extension = strtolower($file->getClientOriginalExtension());
        return in_array($extension, $allowedTypes);
    }

    /**
     * Sanitize file path
     *
     * @param string $path
     * @return string
     */
    private function sanitizePath(string $path): string
    {
        // Remove leading/trailing slashes and normalize
        $path = trim($path, '/');
        
        // Replace multiple slashes with single slash
        $path = preg_replace('/\/+/', '/', $path);
        
        // Remove any potentially dangerous characters including path traversal
        $path = preg_replace('/[^a-zA-Z0-9\/\-_.]/', '', $path);
        
        // Remove any remaining path traversal attempts
        $path = str_replace(['../', '..\\', './', '.\\'], '', $path);
        
        return $path;
    }

    /**
     * Generate unique filename
     *
     * @param UploadedFile $file
     * @param string|null $extension
     * @return string
     */
    private function generateFilename(UploadedFile $file, ?string $extension = null): string
    {
        $extension = $extension ?: $file->getClientOriginalExtension();
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $sanitizedName = Str::slug($originalName);
        
        return $sanitizedName . '_' . uniqid() . '.' . $extension;
    }

    /**
     * Get current disk name
     *
     * @return string
     */
    public function getDisk(): string
    {
        return $this->disk;
    }
}

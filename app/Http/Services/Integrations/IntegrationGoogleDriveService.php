<?php

namespace App\Http\Services\Integrations;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class IntegrationGoogleDriveService
{
    private const LOCK_MKDIR  = 'gdrive_mkdir_';
    private const LOCK_UPLOAD = 'gdrive_upload_';
    private const CACHE_FOLDER = 'gdrive_folder_ready_';

    private const TTL_FOLDER  = 30;   // menit — cache folder exists
    private const TTL_MKDIR   = 20;   // detik — lock buat folder
    private const TTL_UPLOAD  = 30;   // detik — lock upload file
    private const LOCK_WAIT   = 10;   // detik — tunggu lock mkdir
    private const LOCK_WAIT_UPLOAD = 15; // detik — tunggu lock upload

    // ─── List ───────────────────────────────────────────────────────────────

    public function listAll(string $folderPath, bool $recursive = false): array
    {
        return [
            'files'       => $this->listFiles($folderPath, $recursive),
            'directories' => $this->listDirectories($folderPath, $recursive),
        ];
    }

    public function listFiles(string $folderPath, bool $recursive = false): array
    {
        return $recursive
            ? Storage::cloud()->allFiles($folderPath)
            : Storage::cloud()->files($folderPath);
    }

    public function listDirectories(string $folderPath, bool $recursive = false): array
    {
        return $recursive
            ? Storage::cloud()->allDirectories($folderPath)
            : Storage::cloud()->directories($folderPath);
    }

    // ─── Folder Management ──────────────────────────────────────────────────

    public function folderExists(string $folderPath): bool
    {
        return Storage::cloud()->exists($folderPath . '/.gdrivekeep');
    }

    public function deleteFolder(string $folderPath): bool
    {
        try {
            $this->forgetFolderCache($folderPath);
            return Storage::cloud()->deleteDirectory($folderPath);
        } catch (\Throwable $e) {
            Log::warning("Gagal hapus folder cloud [{$folderPath}]: " . $e->getMessage());
            return false;
        }
    }

    // ─── File Management ────────────────────────────────────────────────────

    public function fileExists(string $filePath): bool
    {
        return Storage::cloud()->exists($filePath);
    }

    public function deleteFile(string $filePath): bool
    {
        try {
            return Storage::cloud()->delete($filePath);
        } catch (\Throwable $e) {
            Log::warning("Gagal hapus file cloud [{$filePath}]: " . $e->getMessage());
            return false;
        }
    }

    public function moveFile(string $from, string $to): bool
    {
        try {
            return Storage::cloud()->move($from, $to);
        } catch (\Throwable $e) {
            Log::warning("Gagal move file cloud [{$from} → {$to}]: " . $e->getMessage());
            return false;
        }
    }

    public function copyFile(string $from, string $to): bool
    {
        try {
            return Storage::cloud()->copy($from, $to);
        } catch (\Throwable $e) {
            Log::warning("Gagal copy file cloud [{$from} → {$to}]: " . $e->getMessage());
            return false;
        }
    }

    // ─── Upload ─────────────────────────────────────────────────────────────

    public function upload(string $cloudPath, string $binary): string
    {
        $this->ensureParentFolders(dirname($cloudPath));

        Cache::lock(self::LOCK_UPLOAD . md5($cloudPath), self::TTL_UPLOAD)
            ->block(self::LOCK_WAIT_UPLOAD, fn() => @Storage::cloud()->put($cloudPath, $binary));

        return Storage::cloud()->url($cloudPath);
    }

    public function uploadStream(string $localPath, string $cloudPath): string
    {
        $this->ensureParentFolders(dirname($cloudPath));

        Cache::lock(self::LOCK_UPLOAD . md5($cloudPath), self::TTL_UPLOAD)
            ->block(self::LOCK_WAIT_UPLOAD, function () use ($localPath, $cloudPath) {

                $stream = fopen($localPath, 'r');

                try {
                    @Storage::cloud()->put($cloudPath, $stream);
                } finally {
                    if (is_resource($stream)) {
                        fclose($stream);
                    }
                }
            });

        return Storage::cloud()->url($cloudPath);
    }

    public function replaceFile(string $cloudPath, string $binary): string
    {
        $this->deleteFile($cloudPath);
        return $this->upload($cloudPath, $binary);
    }

    public function cleanupOldFiles(string $folderPath, int $keep = 3): void
    {
        $files = collect($this->listFiles($folderPath))
            ->filter(fn($f) => !str_ends_with($f, '/.gdrivekeep'))
            ->sortDesc()
            ->values();

        foreach ($files->skip($keep) as $oldFile) {
            $this->deleteFile($oldFile);
        }
    }

    // ─── URL & Path ─────────────────────────────────────────────────────────

    public function getUrl(string $cloudPath): string
    {
        return Storage::cloud()->url($cloudPath);
    }

    public function buildPath(string ...$segments): string
    {
        return collect($segments)
            ->map(function ($segment, $index) use ($segments) {

                $isLast = $index === count($segments) - 1;

                // Kalau segment terakhir DAN mengandung extension → anggap filename
                if ($isLast && str_contains($segment, '.')) {
                    return $segment; // JANGAN di-slug
                }

                return str($segment)->slug('_')->toString();
            })
            ->implode('/');
    }

    // ─── Private ────────────────────────────────────────────────────────────

    private function ensureParentFolders(string $folderPath): void
    {
        $current = '';

        foreach (explode('/', $folderPath) as $segment) {
            $current  = $current ? "{$current}/{$segment}" : $segment;
            $cacheKey = self::CACHE_FOLDER . md5($current);

            if (Cache::has($cacheKey)) {
                continue;
            }

            Cache::lock(self::LOCK_MKDIR . md5($current), self::TTL_MKDIR)
                ->block(self::LOCK_WAIT, function () use ($current, $cacheKey) {
                    if (!Storage::cloud()->exists($current . '/.gdrivekeep')) {
                        Storage::cloud()->put($current . '/.gdrivekeep', '');
                    }
                    Cache::put($cacheKey, true, now()->addMinutes(self::TTL_FOLDER));
                });
        }
    }

    private function forgetFolderCache(string $folderPath): void
    {
        $current = '';

        foreach (explode('/', $folderPath) as $segment) {
            $current = $current ? "{$current}/{$segment}" : $segment;
            Cache::forget(self::CACHE_FOLDER . md5($current));
        }
    }
}

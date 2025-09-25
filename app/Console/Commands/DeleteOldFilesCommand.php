<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class DeleteOldFilesCommand extends Command
{
    protected $signature = 'files:delete-old-docs';
    protected $description = 'Delete all files and folders inside public/doc older than 1 hour';

    public function handle()
    {
        $directory = public_path('doc');
        $oneHourAgo = Carbon::now()->subHour();

        \Log::info("🧹 Starting cleanup in: $directory (deleting items older than 1 hour)");

        if (!File::exists($directory)) {
            \Log::warning("❌ Directory not found: $directory");
            return 0;
        }

        // حذف الملفات الأقدم من ساعة
        $allFiles = File::allFiles($directory);
        foreach ($allFiles as $file) {
            try {
                $modifiedAt = Carbon::createFromTimestamp(File::lastModified($file));

                if ($modifiedAt->lt($oneHourAgo)) {
                    File::delete($file->getPathname());
                    \Log::info("🗑 Deleted file: " . $file->getPathname());
                }
            } catch (\Exception $e) {
                \Log::error("⚠️ Error deleting file: " . $file->getPathname() . " - " . $e->getMessage());
            }
        }

        // حذف المجلدات الفارغة بعد حذف الملفات
        $allDirs = File::directories($directory);
        foreach ($allDirs as $dir) {
            try {
                if (empty(File::allFiles($dir))) {
                    File::deleteDirectory($dir);
                    \Log::info("🗑 Deleted empty folder: $dir");
                }
            } catch (\Exception $e) {
                \Log::error("⚠️ Error deleting directory: $dir - " . $e->getMessage());
            }
        }

        \Log::info("✅ Cleanup complete.");
        return 0;
    }
}

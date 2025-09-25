<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\Jobs\DeleteOldFilesJob;

class DeleteOldFilesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $directory;

    public function __construct($directory)
    {
        $this->directory = $directory;
    }

    public function handle()
    {
        if (!File::exists($this->directory)) {
            return;
        }

        $today = Carbon::today();

        foreach (File::files($this->directory) as $file) {
            $createdAt = Carbon::createFromTimestamp(File::lastModified($file));

            // Skip files created today
            if ($createdAt->isSameDay($today)) {
                continue;
            }

         DeleteOldFilesJob::dispatch(public_path('doc'));
        }
    }
}

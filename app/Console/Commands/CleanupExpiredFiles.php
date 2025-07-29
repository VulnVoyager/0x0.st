<?php

namespace App\Console\Commands;

use App\Services\FileService;
use Illuminate\Console\Command;

class CleanupExpiredFiles extends Command
{
    protected $signature = 'files:cleanup';
    protected $description = 'Clean up expired files';

    public function handle(FileService $fileService): int
    {
        $count = $fileService->cleanupExpiredFiles();
        $this->info("Cleaned up {$count} expired files");
        
        return 0;
    }
}
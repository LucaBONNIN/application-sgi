<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class ResetDemoCommand extends Command
{
    protected $signature = 'app:reset-demo';

    protected $description = 'Reset the demo application: fresh migrations with seeds and delete uploaded files.';

    public function handle(): int
    {
        Storage::disk('local')->deleteDirectory('quotations');

        Artisan::call('migrate:fresh', [
            '--seed' => true,
            '--force' => true,
        ]);

        $this->line(Artisan::output());

        return self::SUCCESS;
    }
}

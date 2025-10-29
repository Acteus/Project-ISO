<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CacheService;

class CacheWarmup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'iso:cache-warmup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Warm up application cache with common queries';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting cache warm-up...');

        CacheService::warmUp();

        $this->info('Cache warm-up completed successfully!');

        return 0;
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Services\CacheService;

class CacheStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'iso:cache-stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display cache statistics and information';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=================================');
        $this->info('   Cache Statistics');
        $this->info('=================================');
        $this->newLine();

        // Get cache statistics
        $stats = CacheService::getStatistics();

        $this->info('Cache Driver: ' . config('cache.default'));
        $this->info('Total Tracked Keys: ' . $stats['total_keys']);
        $this->newLine();

        if ($stats['total_keys'] > 0) {
            $this->info('Cached Keys:');
            $this->table(
                ['Key'],
                array_map(fn($key) => [$key], array_slice($stats['keys'], 0, 20))
            );

            if ($stats['total_keys'] > 20) {
                $this->warn('Showing first 20 of ' . $stats['total_keys'] . ' keys');
            }
        } else {
            $this->warn('No cache keys tracked yet.');
        }

        $this->newLine();
        $this->info('Cache Configuration:');
        $durations = CacheService::CACHE_DURATIONS;
        $rows = [];
        foreach ($durations as $type => $duration) {
            $rows[] = [
                $type,
                $duration . 's',
                gmdate('i:s', $duration)
            ];
        }
        $this->table(['Type', 'Duration', 'Time'], $rows);

        return 0;
    }
}

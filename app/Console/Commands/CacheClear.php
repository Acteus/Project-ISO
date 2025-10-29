<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use App\Services\CacheService;

class CacheClear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'iso:cache-clear {--type=all : Type of cache to clear (all, analytics, responses, views, routes, config)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear application cache with options';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type');

        $this->info("Clearing cache type: {$type}");

        switch ($type) {
            case 'analytics':
                CacheService::clearAnalyticsCache();
                $this->info('Analytics cache cleared successfully!');
                break;

            case 'responses':
                CacheService::clearResponseCache();
                $this->info('Response cache cleared successfully!');
                break;

            case 'views':
                Artisan::call('view:clear');
                $this->info('View cache cleared successfully!');
                break;

            case 'routes':
                Artisan::call('route:clear');
                $this->info('Route cache cleared successfully!');
                break;

            case 'config':
                Artisan::call('config:clear');
                $this->info('Config cache cleared successfully!');
                break;

            case 'all':
            default:
                Cache::flush();
                Artisan::call('view:clear');
                Artisan::call('route:clear');
                Artisan::call('config:clear');
                Artisan::call('cache:clear');
                $this->info('All caches cleared successfully!');
                break;
        }

        return 0;
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;
use App\Models\SurveyResponse;
use App\Observers\SurveyResponseObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register model observers for automatic cache clearing
        SurveyResponse::observe(SurveyResponseObserver::class);

        // Enable query logging in development for performance monitoring
        if (config('app.debug')) {
            DB::listen(function ($query) {
                // Log slow queries (> 100ms)
                if ($query->time > 100) {
                    Log::warning('Slow query detected', [
                        'sql' => $query->sql,
                        'bindings' => $query->bindings,
                        'time' => $query->time . 'ms'
                    ]);
                }
            });
        }

        // Share common data with all views if needed
        View::composer('*', function ($view) {
            // Add any global view data here
        });

        // Schedule automated compliance reporting (ISO 21001 Clause 8.2.4)
        Schedule::command('compliance:generate-report --period=monthly --send-email')
            ->monthlyOn(1, '08:00')
            ->timezone('Asia/Manila')
            ->description('Generate monthly ISO 21001 compliance report');

        // Also run weekly compliance reports for monitoring
        Schedule::command('compliance:generate-report --period=weekly')
            ->weekly()
            ->mondays()
            ->at('08:00')
            ->timezone('Asia/Manila')
            ->description('Generate weekly ISO 21001 compliance report');
    }
}

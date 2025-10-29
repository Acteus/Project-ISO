<?php

namespace App\Observers;

use App\Models\SurveyResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Services\CacheService;

class SurveyResponseObserver
{
    /**
     * Handle the SurveyResponse "created" event.
     */
    public function created(SurveyResponse $surveyResponse): void
    {
        Log::info('New survey response created, clearing analytics cache');

        // Clear analytics cache when new response is added
        CacheService::clearAnalyticsCache();

        // Clear specific dashboard caches
        Cache::tags(['analytics', 'dashboard'])->flush();
    }

    /**
     * Handle the SurveyResponse "updated" event.
     */
    public function updated(SurveyResponse $surveyResponse): void
    {
        Log::info('Survey response updated, clearing analytics cache');

        // Clear analytics cache when response is updated
        CacheService::clearAnalyticsCache();

        // Clear specific dashboard caches
        Cache::tags(['analytics', 'dashboard'])->flush();
    }

    /**
     * Handle the SurveyResponse "deleted" event.
     */
    public function deleted(SurveyResponse $surveyResponse): void
    {
        Log::info('Survey response deleted, clearing analytics cache');

        // Clear analytics cache when response is deleted
        CacheService::clearAnalyticsCache();

        // Clear specific dashboard caches
        Cache::tags(['analytics', 'dashboard'])->flush();
    }
}

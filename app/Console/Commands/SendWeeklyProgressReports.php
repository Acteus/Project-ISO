<?php

namespace App\Console\Commands;

use App\Mail\WeeklyProgressReport;
use App\Models\Admin;
use App\Models\WeeklyMetric;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendWeeklyProgressReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-weekly-progress-reports {--week= : Specific week to send report for (YYYY-WW format)} {--test : Send test email to first admin only}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send weekly progress reports to administrators via email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Sending weekly progress reports...');

        $specificWeek = $this->option('week');
        $testMode = $this->option('test');

        // Get the weekly metric to report on
        $weeklyMetric = $this->getWeeklyMetric($specificWeek);

        if (!$weeklyMetric) {
            $this->info('No weekly metrics found for the specified period. Running aggregation first...');
            // Try to aggregate current metrics
            Artisan::call('app:aggregate-weekly-metrics', [], $this->getOutput());
            $weeklyMetric = $this->getWeeklyMetric($specificWeek);

            if (!$weeklyMetric) {
                $this->error('Still no weekly metrics available after aggregation.');
                return 1;
            }
        }

        // Get previous week's metric for comparison
        $previousMetric = WeeklyMetric::where('year', $weeklyMetric->year)
            ->where('week_number', $weeklyMetric->week_number - 1)
            ->first();

        // Get admin recipients
        $admins = $this->getAdminRecipients($testMode);

        if ($admins->isEmpty()) {
            $this->error('No admin recipients found.');
            return 1;
        }

        $this->info("Sending reports to {$admins->count()} administrator(s)...");
        $this->info("Report for: {$weeklyMetric->date_range_label}");

        $sentCount = 0;
        $failedCount = 0;

        foreach ($admins as $admin) {
            try {
                Mail::to($admin->email)->send(new WeeklyProgressReport($weeklyMetric, $previousMetric));
                $this->info("✓ Sent to: {$admin->email}");
                $sentCount++;
            } catch (\Exception $e) {
                $this->error("✗ Failed to send to {$admin->email}: {$e->getMessage()}");
                $failedCount++;
            }
        }

        $this->info("Report sending completed. Sent: {$sentCount}, Failed: {$failedCount}");

        return 0;
    }

    /**
     * Get the weekly metric to report on
     */
    private function getWeeklyMetric($specificWeek = null)
    {
        if ($specificWeek) {
            // Parse specific week format (YYYY-WW)
            [$year, $week] = explode('-W', $specificWeek);
            return WeeklyMetric::where('year', (int) $year)
                ->where('week_number', (int) $week)
                ->first();
        } else {
            // Get the most recent completed week
            return WeeklyMetric::orderBy('week_start_date', 'desc')->first();
        }
    }

    /**
     * Get admin recipients
     */
    private function getAdminRecipients($testMode = false)
    {
        $query = Admin::whereNotNull('email');

        if ($testMode) {
            // Send to first admin only for testing
            return $query->limit(1)->get();
        }

        return $query->get();
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class WeeklyProgressWorkflow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:weekly-progress-workflow {--test : Run in test mode (send to first admin only)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Complete weekly progress workflow: aggregate metrics and send reports';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting Weekly Progress Workflow...');

        $testMode = $this->option('test');

        try {
            // Step 1: Aggregate weekly metrics
            $this->info('📊 Step 1: Aggregating weekly metrics...');
            $exitCode = Artisan::call('app:aggregate-weekly-metrics', [], $this->getOutput());
            if ($exitCode !== 0) {
                throw new \Exception('Failed to aggregate weekly metrics');
            }

            // Step 2: Send weekly progress reports
            $this->info('📧 Step 2: Sending weekly progress reports...');
            $params = $testMode ? ['--test' => true] : [];
            $exitCode = Artisan::call('app:send-weekly-progress-reports', $params, $this->getOutput());
            if ($exitCode !== 0) {
                throw new \Exception('Failed to send weekly progress reports');
            }

            $this->info('✅ Weekly Progress Workflow completed successfully!');

        } catch (\Exception $e) {
            $this->error('❌ Weekly Progress Workflow failed: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}

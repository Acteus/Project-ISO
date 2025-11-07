<?php

namespace App\Console\Commands;

use App\Services\DataRetentionService;
use Illuminate\Console\Command;

/**
 * Data Retention Cleanup Command
 * 
 * Cleans up expired data based on retention policies (GDPR & ISO 27001 compliant)
 * 
 * @package App\Console\Commands
 */
class DataRetentionCleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:retention-cleanup 
                            {--dry-run : Run without actually deleting data}
                            {--stats : Show retention statistics only}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired data based on retention policies (GDPR & ISO 27001 compliant)';

    /**
     * Execute the console command.
     */
    public function handle(DataRetentionService $retentionService)
    {
        $this->info('Starting data retention cleanup...');

        if ($this->option('stats')) {
            $stats = $retentionService->getRetentionStats();
            $this->info('Retention Statistics:');
            $this->table(
                ['Metric', 'Value'],
                [
                    ['Retention Period (days)', $stats['retention_days']],
                    ['Cutoff Date', $stats['cutoff_date']],
                    ['Expired Survey Responses', $stats['expired_survey_responses']],
                    ['Expired Audit Logs', $stats['expired_audit_logs']],
                    ['Expired Consent Records', $stats['expired_consent_records']],
                ]
            );
            return 0;
        }

        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->warn('DRY RUN MODE: No data will be deleted');
        }

        try {
            $stats = $retentionService->cleanupExpiredData($dryRun);

            $this->info('Cleanup completed successfully!');
            $this->table(
                ['Data Type', 'Records Processed'],
                [
                    ['Survey Responses', $stats['survey_responses']],
                    ['Audit Logs', $stats['audit_logs']],
                    ['Consent Records', $stats['consent_records']],
                ]
            );

            if ($dryRun) {
                $this->warn('This was a dry run. Run without --dry-run to actually delete data.');
            }

            return 0;
        } catch (\Exception $e) {
            $this->error('Cleanup failed: ' . $e->getMessage());
            return 1;
        }
    }
}

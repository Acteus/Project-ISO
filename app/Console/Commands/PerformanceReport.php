<?php

namespace App\Console\Commands;

use App\Services\PerformanceMonitoringService;
use Illuminate\Console\Command;

class PerformanceReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'performance:report 
                            {--hours=24 : Number of hours to analyze}
                            {--cleanup : Clean up old metrics after generating report}
                            {--days=30 : Days of metrics to keep when cleaning up}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate performance monitoring report for AI services and analytics queries';

    protected $monitoringService;

    public function __construct(PerformanceMonitoringService $monitoringService)
    {
        parent::__construct();
        $this->monitoringService = $monitoringService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hours = (int) $this->option('hours');
        
        $this->info("Generating performance report for the last {$hours} hours...");
        $this->newLine();

        // Get bottleneck analysis
        $analysis = $this->monitoringService->getBottleneckAnalysis($hours);
        
        // Display overall health
        $health = $analysis['overall_health'];
        $healthColor = match($health) {
            'healthy' => 'green',
            'degraded' => 'yellow',
            'critical' => 'red',
            default => 'white',
        };
        
        $this->line("Overall Health: <fg={$healthColor}>{$health}</>");
        $this->newLine();

        // Display AI Service Summary
        $this->info('=== AI Service Performance ===');
        $aiSummary = $analysis['ai_summary'];
        $this->displayAISummary($aiSummary);
        $this->newLine();

        // Display Analytics Query Summary
        $this->info('=== Analytics Query Performance ===');
        $analyticsSummary = $analysis['analytics_summary'];
        $this->displayAnalyticsSummary($analyticsSummary);
        $this->newLine();

        // Display Bottlenecks
        if (!empty($analysis['bottlenecks'])) {
            $this->info('=== Identified Bottlenecks ===');
            foreach ($analysis['bottlenecks'] as $bottleneck) {
                $severity = $bottleneck['severity'];
                $color = match($severity) {
                    'critical' => 'red',
                    'high' => 'yellow',
                    'medium' => 'cyan',
                    default => 'white',
                };
                
                $this->line("  [<fg={$color}>{$severity}</>] {$bottleneck['issue']}");
                $this->line("      Recommendation: {$bottleneck['recommendation']}");
                $this->newLine();
            }
        } else {
            $this->info('No bottlenecks identified. System is performing well!');
            $this->newLine();
        }

        // Cleanup old metrics if requested
        if ($this->option('cleanup')) {
            $days = (int) $this->option('days');
            $this->info("Cleaning up performance metrics older than {$days} days...");
            $deleted = $this->monitoringService->cleanupOldMetrics($days);
            $this->info("Deleted {$deleted} old performance metrics.");
        }

        $this->info('Performance report generated successfully!');
        
        return Command::SUCCESS;
    }

    protected function displayAISummary(array $summary): void
    {
        $this->line("Total Calls: {$summary['total_calls']}");
        $this->line("Success Rate: {$summary['success_rate']}%");
        $this->line("Average Duration: {$summary['average_duration_ms']}ms");
        $this->line("P95 Duration: {$summary['p95_duration_ms']}ms");
        $this->line("P99 Duration: {$summary['p99_duration_ms']}ms");
        $this->line("Error Count: {$summary['error_count']}");
        $this->line("Slow Calls (>2s): {$summary['slow_calls']} ({$summary['slow_call_rate']}%)");
        
        if (!empty($summary['by_endpoint'])) {
            $this->line("\nTop Endpoints:");
            foreach (array_slice($summary['by_endpoint'], 0, 5, true) as $endpoint => $stats) {
                $this->line("  {$endpoint}: {$stats['count']} calls, avg {$stats['average_duration_ms']}ms, {$stats['success_rate']}% success");
            }
        }
    }

    protected function displayAnalyticsSummary(array $summary): void
    {
        $this->line("Total Queries: {$summary['total_queries']}");
        $this->line("Average Duration: {$summary['average_duration_ms']}ms");
        $this->line("P95 Duration: {$summary['p95_duration_ms']}ms");
        $this->line("P99 Duration: {$summary['p99_duration_ms']}ms");
        $this->line("Slow Queries (>1s): {$summary['slow_queries']} ({$summary['slow_query_rate']}%)");
        $this->line("Total Rows Processed: {$summary['total_rows_processed']}");
        $this->line("Average Rows per Query: {$summary['average_rows_per_query']}");
        
        if (!empty($summary['by_query_type'])) {
            $this->line("\nQuery Types:");
            foreach (array_slice($summary['by_query_type'], 0, 5, true) as $type => $stats) {
                $this->line("  {$type}: {$stats['count']} queries, avg {$stats['average_duration_ms']}ms, {$stats['total_rows']} rows");
            }
        }
    }
}

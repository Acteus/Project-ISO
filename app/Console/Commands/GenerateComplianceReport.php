<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ComplianceReportingService;
use App\Services\AuditService;
use App\Mail\MonthlyComplianceReport;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

/**
 * Command to generate and send automated compliance reports
 * Scheduled to run monthly for ISO 21001 compliance
 */
class GenerateComplianceReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'compliance:generate-report 
                            {--period=monthly : Report period (daily, weekly, monthly)}
                            {--send-email : Send report via email}
                            {--output=json : Output format (json, console)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate automated ISO 21001 compliance status report';

    protected $complianceService;
    protected $auditService;

    public function __construct(ComplianceReportingService $complianceService, AuditService $auditService)
    {
        parent::__construct();
        $this->complianceService = $complianceService;
        $this->auditService = $auditService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $period = $this->option('period');
        $sendEmail = $this->option('send-email');
        $output = $this->option('output');

        // Determine date range based on period
        [$startDate, $endDate] = $this->getDateRange($period);

        $this->info("Generating compliance report for period: {$period}");
        $this->info("Date range: {$startDate->toDateString()} to {$endDate->toDateString()}");

        // Generate compliance report
        $report = $this->complianceService->generateComplianceReport($startDate, $endDate);

        // Log report generation
        $this->auditService->logCompliance(
            'generate_compliance_report',
            "Generated automated compliance report for {$period} period",
            null,
            [
                'period' => $period,
                'start_date' => $startDate->toISOString(),
                'end_date' => $endDate->toISOString(),
                'overall_status' => $report['overall_compliance_status']['status'] ?? 'Unknown',
            ]
        );

        // Output report
        if ($output === 'json') {
            $this->line(json_encode($report, JSON_PRETTY_PRINT));
        } else {
            $this->displayConsoleReport($report);
        }

        // Send email if requested
        if ($sendEmail) {
            $this->sendEmailReport($report, $period);
        }

        $this->info('Compliance report generated successfully!');
        
        return Command::SUCCESS;
    }

    /**
     * Get date range based on period
     */
    protected function getDateRange(string $period): array
    {
        return match($period) {
            'daily' => [now()->subDay(), now()],
            'weekly' => [now()->subWeek(), now()],
            'monthly' => [now()->subMonth(), now()],
            default => [now()->subMonth(), now()],
        };
    }

    /**
     * Display report in console
     */
    protected function displayConsoleReport(array $report): void
    {
        $this->newLine();
        $this->info('=== ISO 21001 Compliance Report ===');
        $this->newLine();
        
        $overall = $report['overall_compliance_status'];
        $this->line("Overall Status: {$overall['status']} (Score: {$overall['score']}%)");
        $this->newLine();

        // Audit Trail Compliance
        $auditTrail = $report['audit_trail_compliance'];
        $this->line("Audit Trail Compliance: " . ($auditTrail['compliant'] ? '✓ Compliant' : '✗ Non-Compliant'));
        $this->line("  - Total Activities: {$auditTrail['total_activities']}");
        $this->line("  - With Timestamps: {$auditTrail['activities_with_timestamp']}");
        $this->line("  - With IP Addresses: {$auditTrail['activities_with_ip']}");
        $this->newLine();

        // Access Monitoring
        $accessMonitoring = $report['access_monitoring_compliance'];
        $this->line("Access Monitoring: " . ($accessMonitoring['compliant'] ? '✓ Compliant' : '✗ Non-Compliant'));
        $this->line("  - Access Events: {$accessMonitoring['total_access_events']}");
        $this->line("  - Modification Events: {$accessMonitoring['total_modification_events']}");
        $this->newLine();

        // Traceability
        $traceability = $report['traceability_compliance'];
        $this->line("Traceability (8.2.4): " . ($traceability['compliant'] ? '✓ Compliant' : '✗ Non-Compliant'));
        $this->line("  - Compliance Rate: {$traceability['compliance_rate']}%");
        $this->line("  - Logs with Resource Type: {$traceability['logs_with_resource_type']}");
        $this->line("  - Logs with Resource ID: {$traceability['logs_with_resource_id']}");
        $this->newLine();

        // ISO 21001 Clause 8.2.4
        $iso824 = $report['iso_21001_clause_8_2_4_compliance'];
        $this->line("ISO 21001 Clause 8.2.4: " . ($iso824['compliant'] ? '✓ Fully Compliant' : '✗ Non-Compliant'));
        $this->newLine();
    }

    /**
     * Send email report
     */
    protected function sendEmailReport(array $report, string $period): void
    {
        try {
            // Get admin email from config or use default
            $adminEmail = config('mail.admin_email', config('mail.from.address'));
            
            if (!$adminEmail) {
                $this->warn('No admin email configured. Skipping email send.');
                return;
            }

            Mail::to($adminEmail)->send(new MonthlyComplianceReport($report, $period));
            $this->info("Compliance report sent to {$adminEmail}");
        } catch (\Exception $e) {
            $this->error("Failed to send compliance report email: {$e->getMessage()}");
        }
    }
}


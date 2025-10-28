<?php

namespace App\Console\Commands;

use App\Models\Admin;
use App\Services\VisualizationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use PDF; // Assuming you have a PDF package like barryvdh/laravel-dompdf

class GenerateMonthlyReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-monthly-reports {--month= : Specific month (YYYY-MM format)} {--year= : Specific year} {--test : Send test email to first admin only}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate and send comprehensive monthly ISO 21001 compliance reports';

    protected $visualizationService;

    public function __construct(VisualizationService $visualizationService)
    {
        parent::__construct();
        $this->visualizationService = $visualizationService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🗓️ Generating monthly ISO 21001 compliance reports...');

        $month = $this->option('month') ?: now()->format('Y-m');
        $year = $this->option('year') ?: now()->year;
        $testMode = $this->option('test');

        if (str_contains($month, '-')) {
            [$year, $monthNum] = explode('-', $month);
        } else {
            $monthNum = $month;
        }

        $this->info("Generating report for {$year}-{$monthNum}");

        try {
            // Generate monthly report data
            $reportData = $this->visualizationService->generateMonthlyReportData($year, $monthNum);

            if (empty($reportData['weekly_data'])) {
                $this->warn("No data available for {$year}-{$monthNum}");
                return 1;
            }

            // Generate PDF report
            $pdfPath = $this->generatePDFReport($reportData);

            // Send emails with PDF attachment
            $this->sendMonthlyReportEmails($reportData, $pdfPath, $testMode);

            // Clean up temporary file
            Storage::delete($pdfPath);

            $this->info('✅ Monthly report generation completed successfully!');

        } catch (\Exception $e) {
            $this->error('❌ Monthly report generation failed: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Generate PDF report
     */
    private function generatePDFReport($reportData)
    {
        // Create PDF content (you would typically use a view for this)
        $html = $this->buildMonthlyReportHTML($reportData);

        // Generate PDF (assuming you have dompdf or similar package)
        // For now, we'll create a simple HTML file as placeholder
        $filename = 'monthly-report-' . $reportData['year'] . '-' . str_pad($reportData['month'], 2, '0', STR_PAD_LEFT) . '.html';
        $path = 'temp/' . $filename;

        Storage::put($path, $html);

        return $path;
    }

    /**
     * Build HTML content for monthly report
     */
    private function buildMonthlyReportHTML($reportData)
    {
        $html = "
        <!DOCTYPE html>
        <html>
        <head>
            <title>Monthly ISO 21001 Compliance Report - {$reportData['month']}</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 40px; }
                .header { background: #4285f4; color: white; padding: 20px; text-align: center; }
                .section { margin: 20px 0; padding: 20px; border: 1px solid #ddd; }
                .metric { display: inline-block; width: 30%; margin: 10px 1%; text-align: center; }
                .metric-value { font-size: 24px; font-weight: bold; color: #4285f4; }
                .metric-label { font-size: 14px; color: #666; }
                table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background: #f5f5f5; }
                .status-good { color: #28a745; }
                .status-warning { color: #ffc107; }
                .status-danger { color: #dc3545; }
            </style>
        </head>
        <body>
            <div class='header'>
                <h1>Monthly ISO 21001 Compliance Report</h1>
                <h2>{$reportData['month']}</h2>
                <p>Quality Education Metrics & Continuous Improvement Analysis</p>
            </div>

            <div class='section'>
                <h3>Monthly Overview</h3>
                <div class='metric'>
                    <div class='metric-value'>{$reportData['monthly_averages']['overall_satisfaction']}</div>
                    <div class='metric-label'>Avg Satisfaction</div>
                </div>
                <div class='metric'>
                    <div class='metric-value'>{$reportData['monthly_averages']['compliance_score']}</div>
                    <div class='metric-label'>Avg Compliance</div>
                </div>
                <div class='metric'>
                    <div class='metric-value'>{$reportData['monthly_averages']['total_responses']}</div>
                    <div class='metric-label'>Total Responses</div>
                </div>
            </div>

            <div class='section'>
                <h3>Weekly Breakdown</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Week</th>
                            <th>Satisfaction</th>
                            <th>Compliance</th>
                            <th>Safety</th>
                            <th>Responses</th>
                        </tr>
                    </thead>
                    <tbody>";

        foreach ($reportData['weekly_data'] as $week) {
            $html .= "
                        <tr>
                            <td>{$week->date_range_label}</td>
                            <td>{$week->overall_satisfaction}</td>
                            <td>{$week->compliance_score}</td>
                            <td>{$week->safety_index}</td>
                            <td>{$week->new_responses}</td>
                        </tr>";
        }

        $html .= "
                    </tbody>
                </table>
            </div>

            <div class='section'>
                <h3>Target Achievement</h3>
                <p>Satisfaction Target (4.0): <span class='" . ($reportData['targets_achieved']['satisfaction'] ? 'status-good' : 'status-danger') . "'>" .
                    ($reportData['targets_achieved']['satisfaction'] ? 'ACHIEVED' : 'NOT ACHIEVED') . "</span></p>
                <p>Compliance Target (80%): <span class='" . ($reportData['targets_achieved']['compliance'] ? 'status-good' : 'status-danger') . "'>" .
                    ($reportData['targets_achieved']['compliance'] ? 'ACHIEVED' : 'NOT ACHIEVED') . "</span></p>
            </div>

            <div class='section'>
                <h3>Key Insights & Recommendations</h3>
                <p>This comprehensive monthly report provides detailed analysis of ISO 21001 compliance metrics and quality education performance trends.</p>
                <ul>
                    <li>Monitor weekly progress against established targets</li>
                    <li>Address any areas showing declining trends</li>
                    <li>Celebrate achievements and maintain high standards</li>
                </ul>
            </div>
        </body>
        </html>";

        return $html;
    }

    /**
     * Send monthly report emails
     */
    private function sendMonthlyReportEmails($reportData, $pdfPath, $testMode = false)
    {
        $admins = $this->getAdminRecipients($testMode);

        if ($admins->isEmpty()) {
            $this->error('No admin recipients found.');
            return;
        }

        $this->info("Sending monthly reports to {$admins->count()} administrator(s)...");

        $sentCount = 0;
        $failedCount = 0;

        foreach ($admins as $admin) {
            try {
                Mail::to($admin->email)->send(new \App\Mail\MonthlyComplianceReport($reportData, $pdfPath));
                $this->info("✓ Sent to: {$admin->email}");
                $sentCount++;
            } catch (\Exception $e) {
                $this->error("✗ Failed to send to {$admin->email}: {$e->getMessage()}");
                $failedCount++;
            }
        }

        $this->info("Monthly report emails sent. Success: {$sentCount}, Failed: {$failedCount}");
    }

    /**
     * Get admin recipients
     */
    private function getAdminRecipients($testMode = false)
    {
        $query = Admin::whereNotNull('email');

        if ($testMode) {
            return $query->limit(1)->get();
        }

        return $query->get();
    }
}

<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\SurveyResponse;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Compliance Reporting Service
 * Generates automated compliance status reports for ISO 21001
 */
class ComplianceReportingService
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    /**
     * Generate comprehensive compliance status report
     *
     * @param Carbon|null $startDate
     * @param Carbon|null $endDate
     * @return array
     */
    public function generateComplianceReport(?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $startDate = $startDate ?? now()->subMonth();
        $endDate = $endDate ?? now();

        return [
            'report_period' => [
                'start_date' => $startDate->toISOString(),
                'end_date' => $endDate->toISOString(),
                'generated_at' => now()->toISOString(),
            ],
            'audit_trail_compliance' => $this->checkAuditTrailCompliance($startDate, $endDate),
            'access_monitoring_compliance' => $this->checkAccessMonitoringCompliance($startDate, $endDate),
            'traceability_compliance' => $this->checkTraceabilityCompliance($startDate, $endDate),
            'data_integrity_compliance' => $this->checkDataIntegrityCompliance($startDate, $endDate),
            'overall_compliance_status' => $this->calculateOverallComplianceStatus($startDate, $endDate),
            'iso_21001_clause_8_2_4_compliance' => $this->checkISO21001Clause824Compliance($startDate, $endDate),
        ];
    }

    /**
     * Check audit trail compliance
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array
     */
    protected function checkAuditTrailCompliance(Carbon $startDate, Carbon $endDate): array
    {
        $totalActivities = AuditLog::whereBetween('created_at', [$startDate, $endDate])->count();
        $activitiesWithTimestamp = AuditLog::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('created_at')
            ->count();
        $activitiesWithIp = AuditLog::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('ip_address')
            ->count();

        $complianceRate = $totalActivities > 0 
            ? (($activitiesWithTimestamp + $activitiesWithIp) / ($totalActivities * 2)) * 100 
            : 100;

        return [
            'compliant' => $complianceRate >= 100,
            'compliance_rate' => round($complianceRate, 2),
            'total_activities' => $totalActivities,
            'activities_with_timestamp' => $activitiesWithTimestamp,
            'activities_with_ip' => $activitiesWithIp,
            'requirements' => [
                'all_activities_logged' => $totalActivities > 0,
                'timestamps_present' => $activitiesWithTimestamp === $totalActivities,
                'ip_addresses_present' => $activitiesWithIp === $totalActivities,
            ],
        ];
    }

    /**
     * Check access monitoring compliance
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array
     */
    protected function checkAccessMonitoringCompliance(Carbon $startDate, Carbon $endDate): array
    {
        $dataAccessEvents = AuditLog::whereBetween('created_at', [$startDate, $endDate])
            ->where('action', 'data_access')
            ->count();

        $dataModificationEvents = AuditLog::whereBetween('created_at', [$startDate, $endDate])
            ->where('action', 'data_modification')
            ->count();

        $totalDataOperations = $dataAccessEvents + $dataModificationEvents;

        // Check if critical resources have access logs
        $surveyResponseAccesses = AuditLog::whereBetween('created_at', [$startDate, $endDate])
            ->where('resource_type', 'survey_response')
            ->where('action', 'data_access')
            ->count();

        $surveyResponseModifications = AuditLog::whereBetween('created_at', [$startDate, $endDate])
            ->where('resource_type', 'survey_response')
            ->where('action', 'data_modification')
            ->count();

        return [
            'compliant' => $totalDataOperations > 0,
            'total_access_events' => $dataAccessEvents,
            'total_modification_events' => $dataModificationEvents,
            'survey_response_accesses' => $surveyResponseAccesses,
            'survey_response_modifications' => $surveyResponseModifications,
            'requirements' => [
                'data_access_logged' => $dataAccessEvents > 0,
                'data_modifications_logged' => $dataModificationEvents > 0,
                'critical_resources_tracked' => $surveyResponseAccesses > 0 || $surveyResponseModifications > 0,
            ],
        ];
    }

    /**
     * Check traceability compliance (ISO 21001 Clause 8.2.4)
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array
     */
    protected function checkTraceabilityCompliance(Carbon $startDate, Carbon $endDate): array
    {
        $totalLogs = AuditLog::whereBetween('created_at', [$startDate, $endDate])->count();
        
        $logsWithResourceType = AuditLog::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('resource_type')
            ->count();

        $logsWithResourceId = AuditLog::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('resource_id')
            ->count();

        $logsWithOldValues = AuditLog::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('old_values')
            ->where('action', 'data_modification')
            ->count();

        $modificationEvents = AuditLog::whereBetween('created_at', [$startDate, $endDate])
            ->where('action', 'data_modification')
            ->count();

        $traceabilityRate = $totalLogs > 0 
            ? (($logsWithResourceType + $logsWithResourceId) / ($totalLogs * 2)) * 100 
            : 100;

        return [
            'compliant' => $traceabilityRate >= 95 && ($modificationEvents === 0 || $logsWithOldValues === $modificationEvents),
            'compliance_rate' => round($traceabilityRate, 2),
            'total_logs' => $totalLogs,
            'logs_with_resource_type' => $logsWithResourceType,
            'logs_with_resource_id' => $logsWithResourceId,
            'modification_events' => $modificationEvents,
            'modifications_with_old_values' => $logsWithOldValues,
            'requirements' => [
                'resource_type_tracked' => $logsWithResourceType === $totalLogs,
                'resource_id_tracked' => $logsWithResourceId >= ($totalLogs * 0.9), // Allow some nulls for list operations
                'modifications_have_old_values' => $modificationEvents === 0 || $logsWithOldValues === $modificationEvents,
            ],
        ];
    }

    /**
     * Check data integrity compliance
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array
     */
    protected function checkDataIntegrityCompliance(Carbon $startDate, Carbon $endDate): array
    {
        $totalResponses = SurveyResponse::whereBetween('created_at', [$startDate, $endDate])->count();
        
        $responsesWithAuditLogs = DB::table('survey_responses')
            ->join('audit_logs', function($join) use ($startDate, $endDate) {
                $join->on('survey_responses.id', '=', DB::raw("CAST(audit_logs.resource_id AS UNSIGNED)"))
                     ->where('audit_logs.resource_type', '=', 'survey_response')
                     ->whereBetween('audit_logs.created_at', [$startDate, $endDate]);
            })
            ->whereBetween('survey_responses.created_at', [$startDate, $endDate])
            ->distinct()
            ->count('survey_responses.id');

        $integrityRate = $totalResponses > 0 
            ? ($responsesWithAuditLogs / $totalResponses) * 100 
            : 100;

        return [
            'compliant' => $integrityRate >= 95,
            'compliance_rate' => round($integrityRate, 2),
            'total_responses' => $totalResponses,
            'responses_with_audit_logs' => $responsesWithAuditLogs,
            'requirements' => [
                'all_data_changes_tracked' => $integrityRate >= 95,
            ],
        ];
    }

    /**
     * Check ISO 21001 Clause 8.2.4 specific compliance
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array
     */
    protected function checkISO21001Clause824Compliance(Carbon $startDate, Carbon $endDate): array
    {
        $auditTrail = $this->checkAuditTrailCompliance($startDate, $endDate);
        $traceability = $this->checkTraceabilityCompliance($startDate, $endDate);
        $accessMonitoring = $this->checkAccessMonitoringCompliance($startDate, $endDate);

        $allCompliant = $auditTrail['compliant'] 
            && $traceability['compliant'] 
            && $accessMonitoring['compliant'];

        return [
            'compliant' => $allCompliant,
            'clause' => '8.2.4 (Traceability)',
            'requirements' => [
                'complete_audit_trail' => $auditTrail['compliant'],
                'traceability_of_data' => $traceability['compliant'],
                'access_monitoring' => $accessMonitoring['compliant'],
            ],
            'detailed_compliance' => [
                'audit_trail' => $auditTrail,
                'traceability' => $traceability,
                'access_monitoring' => $accessMonitoring,
            ],
        ];
    }

    /**
     * Calculate overall compliance status
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array
     */
    protected function calculateOverallComplianceStatus(Carbon $startDate, Carbon $endDate): array
    {
        $auditTrail = $this->checkAuditTrailCompliance($startDate, $endDate);
        $accessMonitoring = $this->checkAccessMonitoringCompliance($startDate, $endDate);
        $traceability = $this->checkTraceabilityCompliance($startDate, $endDate);
        $dataIntegrity = $this->checkDataIntegrityCompliance($startDate, $endDate);

        $overallScore = (
            ($auditTrail['compliance_rate'] ?? ($auditTrail['compliant'] ? 100 : 0)) * 0.25 +
            ($accessMonitoring['compliant'] ? 100 : 0) * 0.25 +
            ($traceability['compliance_rate'] ?? ($traceability['compliant'] ? 100 : 0)) * 0.25 +
            ($dataIntegrity['compliance_rate'] ?? ($dataIntegrity['compliant'] ? 100 : 0)) * 0.25
        );

        $status = match(true) {
            $overallScore >= 95 => 'Fully Compliant',
            $overallScore >= 85 => 'Mostly Compliant',
            $overallScore >= 70 => 'Partially Compliant',
            default => 'Non-Compliant',
        };

        return [
            'score' => round($overallScore, 2),
            'status' => $status,
            'compliant' => $overallScore >= 95,
            'components' => [
                'audit_trail' => $auditTrail['compliant'] ?? false,
                'access_monitoring' => $accessMonitoring['compliant'] ?? false,
                'traceability' => $traceability['compliant'] ?? false,
                'data_integrity' => $dataIntegrity['compliant'] ?? false,
            ],
        ];
    }

    /**
     * Get compliance metrics for dashboard
     *
     * @return array
     */
    public function getComplianceMetrics(): array
    {
        $last30Days = now()->subDays(30);
        
        return [
            'last_30_days' => $this->generateComplianceReport($last30Days, now()),
            'last_7_days' => $this->generateComplianceReport(now()->subDays(7), now()),
            'today' => $this->generateComplianceReport(now()->startOfDay(), now()),
        ];
    }
}



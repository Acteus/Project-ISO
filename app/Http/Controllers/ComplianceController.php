<?php

namespace App\Http\Controllers;

use App\Services\ComplianceReportingService;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

/**
 * Compliance Controller
 * Handles compliance reporting and audit trail access
 */
class ComplianceController extends Controller
{
    protected $complianceService;
    protected $auditService;

    public function __construct(ComplianceReportingService $complianceService, AuditService $auditService)
    {
        $this->complianceService = $complianceService;
        $this->auditService = $auditService;
    }

    /**
     * Get compliance status report
     */
    public function getComplianceStatus(Request $request)
    {
        // Log compliance report access
        $this->auditService->logCompliance(
            'view_compliance_report',
            'Accessed compliance status report',
            $request,
            [
                'iso_clause' => '8.2.4',
            ]
        );

        $startDate = $request->input('start_date') 
            ? Carbon::parse($request->input('start_date')) 
            : now()->subMonth();
        $endDate = $request->input('end_date') 
            ? Carbon::parse($request->input('end_date')) 
            : now();

        $report = $this->complianceService->generateComplianceReport($startDate, $endDate);

        return response()->json([
            'success' => true,
            'data' => $report,
        ]);
    }

    /**
     * Get compliance metrics for dashboard
     */
    public function getComplianceMetrics(Request $request)
    {
        // Log compliance metrics access
        $this->auditService->logCompliance(
            'view_compliance_metrics',
            'Accessed compliance metrics',
            $request
        );

        $metrics = $this->complianceService->getComplianceMetrics();

        return response()->json([
            'success' => true,
            'data' => $metrics,
        ]);
    }

    /**
     * Get audit trail for a specific resource
     */
    public function getResourceAuditTrail(Request $request, string $resourceType, $resourceId)
    {
        // Log audit trail access
        $this->auditService->logDataAccess(
            'audit_log',
            $resourceId,
            'view_audit_trail',
            $request,
            [
                'resource_type' => $resourceType,
            ]
        );

        $auditTrail = $this->auditService->getResourceAuditTrail($resourceType, $resourceId);

        return response()->json([
            'success' => true,
            'data' => $auditTrail,
        ]);
    }

    /**
     * Get audit logs with filters
     */
    public function getAuditLogs(Request $request)
    {
        // Log audit logs access
        $this->auditService->logDataAccess(
            'audit_log',
            null,
            'list_audit_logs',
            $request
        );

        $filters = [
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'action' => $request->input('action'),
            'resource_type' => $request->input('resource_type'),
            'user_id' => $request->input('user_id'),
        ];

        $logs = $this->auditService->getAuditLogsForCompliance($filters);

        return response()->json([
            'success' => true,
            'data' => $logs,
        ]);
    }
}


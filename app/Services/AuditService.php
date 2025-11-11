<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Centralized Audit Service for ISO 21001 Compliance
 * Ensures complete audit trail with timestamps and IP addresses
 * Implements Clause 8.2.4 (Traceability)
 */
class AuditService
{
    /**
     * Log a system activity with complete audit information
     *
     * @param string $action Action being performed
     * @param string $description Human-readable description
     * @param Request|null $request HTTP request (for IP address extraction)
     * @param array $metadata Additional metadata
     * @return AuditLog
     */
    public function log(string $action, string $description, ?Request $request = null, array $metadata = []): AuditLog
    {
        $userId = $this->getUserId();
        $ipAddress = $request ? $request->ip() : request()->ip();

        $logData = [
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'ip_address' => $ipAddress,
            'old_values' => $metadata['old_values'] ?? null,
            'new_values' => $metadata['new_values'] ?? null,
            'resource_type' => $metadata['resource_type'] ?? null,
            'resource_id' => $metadata['resource_id'] ?? null,
            'metadata' => $metadata['metadata'] ?? null,
        ];

        try {
            return AuditLog::create($logData);
        } catch (\Exception $e) {
            // Log to Laravel log if database logging fails
            Log::error('Audit log creation failed', [
                'action' => $action,
                'description' => $description,
                'error' => $e->getMessage(),
                'ip_address' => $ipAddress,
            ]);
            
            // Re-throw to ensure we know about audit failures
            throw $e;
        }
    }

    /**
     * Log data access event (ISO 21001:8.2.4 - Access Monitoring)
     *
     * @param string $resourceType Type of resource being accessed
     * @param mixed $resourceId ID of the resource
     * @param string $action Specific action (view, list, export, etc.)
     * @param Request|null $request HTTP request
     * @param array $additionalData Additional data to log
     * @return AuditLog
     */
    public function logDataAccess(string $resourceType, $resourceId, string $action, ?Request $request = null, array $additionalData = []): AuditLog
    {
        $description = sprintf(
            'Accessed %s resource (ID: %s) - Action: %s',
            $resourceType,
            $resourceId ?? 'multiple',
            $action
        );

        return $this->log(
            'data_access',
            $description,
            $request,
            [
                'resource_type' => $resourceType,
                'resource_id' => $resourceId,
                'action' => $action,
                'new_values' => array_merge([
                    'resource_type' => $resourceType,
                    'resource_id' => $resourceId,
                    'access_action' => $action,
                ], $additionalData),
            ]
        );
    }

    /**
     * Log data modification event
     *
     * @param string $resourceType Type of resource being modified
     * @param mixed $resourceId ID of the resource
     * @param string $action Action being performed (create, update, delete)
     * @param array|null $oldValues Previous values (for updates/deletes)
     * @param array|null $newValues New values (for creates/updates)
     * @param Request|null $request HTTP request
     * @return AuditLog
     */
    public function logDataModification(
        string $resourceType,
        $resourceId,
        string $action,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?Request $request = null
    ): AuditLog {
        $descriptions = [
            'create' => 'Created %s resource (ID: %s)',
            'update' => 'Updated %s resource (ID: %s)',
            'delete' => 'Deleted %s resource (ID: %s)',
        ];

        $description = sprintf(
            $descriptions[$action] ?? 'Modified %s resource (ID: %s) - Action: %s',
            $resourceType,
            $resourceId,
            $action
        );

        return $this->log(
            'data_modification',
            $description,
            $request,
            [
                'resource_type' => $resourceType,
                'resource_id' => $resourceId,
                'action' => $action,
                'old_values' => $oldValues,
                'new_values' => $newValues,
            ]
        );
    }

    /**
     * Log authentication event
     *
     * @param string $action login, logout, password_reset, etc.
     * @param bool $success Whether the action was successful
     * @param Request|null $request HTTP request
     * @param array $additionalData Additional data
     * @return AuditLog
     */
    public function logAuthentication(string $action, bool $success = true, ?Request $request = null, array $additionalData = []): AuditLog
    {
        $status = $success ? 'successful' : 'failed';
        $description = sprintf('Authentication: %s %s', $action, $status);

        // Get user ID from additional data or current user for resource_id
        $resourceId = $additionalData['user_id'] ?? $additionalData['admin_id'] ?? $this->getUserId();

        // SECURITY FIX: Log failed authentication attempts to security log channel
        if (!$success && $action === 'login') {
            $ipAddress = $request ? $request->ip() : request()->ip();
            $userAgent = $request ? $request->userAgent() : request()->userAgent();
            $userType = $additionalData['user_type'] ?? 'unknown';
            $identifier = $additionalData['email'] ?? $additionalData['login_value'] ?? 'unknown';
            $reason = $additionalData['reason'] ?? 'unknown';

            Log::channel('security')->warning('Failed login attempt', [
                'action' => $action,
                'user_type' => $userType,
                'identifier' => $identifier,
                'reason' => $reason,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'timestamp' => now()->toIso8601String(),
            ]);
        }

        return $this->log(
            'authentication',
            $description,
            $request,
            [
                'resource_type' => 'session', // Authentication events relate to user sessions
                'resource_id' => $resourceId,
                'new_values' => array_merge([
                    'auth_action' => $action,
                    'success' => $success,
                ], $additionalData),
            ]
        );
    }

    /**
     * Log compliance-related event
     *
     * @param string $action Compliance action
     * @param string $description Description
     * @param Request|null $request HTTP request
     * @param array $complianceData Compliance-specific data
     * @return AuditLog
     */
    public function logCompliance(string $action, string $description, ?Request $request = null, array $complianceData = []): AuditLog
    {
        return $this->log(
            'compliance',
            $description,
            $request,
            [
                'resource_type' => 'compliance',
                'new_values' => array_merge([
                    'compliance_action' => $action,
                    'iso_21001_clause' => $complianceData['iso_clause'] ?? '8.2.4',
                ], $complianceData),
            ]
        );
    }

    /**
     * Get user ID from various authentication sources
     *
     * @return int|null
     */
    protected function getUserId(): ?int
    {
        // Check standard Laravel auth
        if (Auth::check()) {
            return Auth::id();
        }

        // Check Sanctum guard
        if (Auth::guard('sanctum')->check()) {
            return Auth::guard('sanctum')->id();
        }

        // Check admin session (handle both array and object formats)
        if (session()->has('admin')) {
            $admin = session('admin');
            // Handle both array and object formats for backward compatibility
            return is_array($admin) ? ($admin['id'] ?? null) : ($admin->id ?? null);
        }

        return null;
    }

    /**
     * Get audit trail for a specific resource
     *
     * @param string $resourceType
     * @param mixed $resourceId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getResourceAuditTrail(string $resourceType, $resourceId)
    {
        return AuditLog::where('resource_type', $resourceType)
            ->where('resource_id', $resourceId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get all audit logs for compliance reporting
     *
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAuditLogsForCompliance(array $filters = [])
    {
        $query = AuditLog::query();

        if (isset($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        if (isset($filters['action'])) {
            $query->where('action', $filters['action']);
        }

        if (isset($filters['resource_type'])) {
            $query->where('resource_type', $filters['resource_type']);
        }

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }
}


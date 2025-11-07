<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\AuditService;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to automatically log data access events
 * Ensures ISO 21001 Clause 8.2.4 compliance for access monitoring
 */
class AuditMiddleware
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    /**
     * Handle an incoming request and log data access events
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only log for authenticated users
        if (!auth()->check() && !auth()->guard('sanctum')->check() && !session()->has('admin')) {
            return $next($request);
        }

        // Log data access events for specific routes
        $this->logDataAccessIfNeeded($request);

        $response = $next($request);

        // Log after response for routes that access resources
        $this->logResponseDataAccess($request, $response);

        return $response;
    }

    /**
     * Log data access before processing if route accesses data
     */
    protected function logDataAccessIfNeeded(Request $request): void
    {
        $route = $request->route();
        $routeName = $route ? $route->getName() : null;
        $action = $route ? $route->getActionName() : null;

        // Log analytics access
        if (str_contains($routeName ?? '', 'analytics') || str_contains($action ?? '', 'AnalyticsController')) {
            $this->auditService->log(
                'data_access',
                'Accessed analytics dashboard',
                $request,
                [
                    'resource_type' => 'analytics',
                    'metadata' => [
                        'route' => $routeName,
                        'query_params' => $request->query(),
                    ],
                ]
            );
        }

        // Log survey response access
        if (str_contains($routeName ?? '', 'response') || str_contains($action ?? '', 'SurveyController@get')) {
            $resourceId = $request->route('id');
            if ($resourceId) {
                $this->auditService->logDataAccess(
                    'survey_response',
                    $resourceId,
                    'view',
                    $request
                );
            } else {
                $this->auditService->logDataAccess(
                    'survey_response',
                    null,
                    'list',
                    $request
                );
            }
        }

        // Log export operations
        if (str_contains($routeName ?? '', 'export') || str_contains($action ?? '', 'ExportController')) {
            $this->auditService->log(
                'data_access',
                'Accessed export functionality',
                $request,
                [
                    'resource_type' => 'export',
                    'metadata' => [
                        'format' => $request->query('format', 'excel'),
                        'query_params' => $request->query(),
                    ],
                ]
            );
        }
    }

    /**
     * Log data access after response is generated
     */
    protected function logResponseDataAccess(Request $request, Response $response): void
    {
        // Only log successful responses (2xx status codes)
        if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
            return;
        }

        $route = $request->route();
        $routeName = $route ? $route->getName() : null;

        // Log AI service access
        if (str_contains($routeName ?? '', 'ai') || str_contains($request->path(), '/ai/')) {
            $this->auditService->log(
                'data_access',
                'Accessed AI service',
                $request,
                [
                    'resource_type' => 'ai_service',
                    'metadata' => [
                        'path' => $request->path(),
                        'method' => $request->method(),
                    ],
                ]
            );
        }

        // Log visualization access
        if (str_contains($routeName ?? '', 'visualization') || str_contains($request->path(), '/visualization/')) {
            $this->auditService->log(
                'data_access',
                'Accessed visualization',
                $request,
                [
                    'resource_type' => 'visualization',
                    'metadata' => [
                        'path' => $request->path(),
                    ],
                ]
            );
        }
    }
}



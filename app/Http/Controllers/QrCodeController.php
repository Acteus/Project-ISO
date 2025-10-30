<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class QrCodeController extends Controller
{
    protected $qrCodeService;

    public function __construct(QrCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }

    /**
     * Check if admin is authenticated
     */
    private function checkAdminAuth()
    {
        $admin = session('admin');
        if (!$admin) {
            return redirect()->route('student.login');
        }
        return $admin;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $admin = $this->checkAdminAuth();
        if (!$admin instanceof \App\Models\Admin) return $admin;

        // Get filter parameters
        $track = $request->get('track');
        $gradeLevel = $request->get('grade_level');
        $section = $request->get('section');
        $isActive = $request->get('is_active');

        // Build query
        $query = QrCode::query();

        if ($track) {
            $query->where('track', $track);
        }

        if ($gradeLevel) {
            $query->where('grade_level', $gradeLevel);
        }

        if ($section) {
            $query->where('section', $section);
        }

        if ($isActive !== null && $isActive !== '') {
            $query->where('is_active', $isActive);
        }

        $qrCodes = $query->orderBy('track')
                        ->orderBy('grade_level')
                        ->orderBy('section')
                        ->paginate(15);

        // Get statistics
        $stats = $this->qrCodeService->getStatistics([
            'track' => $track,
            'grade_level' => $gradeLevel,
            'section' => $section,
            'is_active' => $isActive
        ]);

        // Get unique values for filters
        $tracks = QrCode::distinct()->pluck('track')->sort();
        $gradeLevels = QrCode::distinct()->whereNotNull('grade_level')->pluck('grade_level')->sort();
        $sections = QrCode::distinct()->whereNotNull('section')->pluck('section')->sort();

        // Provide default values if no QR codes exist
        if ($tracks->isEmpty()) {
            $tracks = collect(['CSS']);
        }
        if ($gradeLevels->isEmpty()) {
            $gradeLevels = collect(['11', '12']);
        }
        if ($sections->isEmpty()) {
            $sections = collect(['C11a', 'C11b', 'C11c', 'C12a', 'C12b', 'C12c']);
        }

        return view('admin.qr-codes.index', compact(
            'qrCodes', 'stats', 'tracks', 'gradeLevels', 'sections',
            'track', 'gradeLevel', 'section', 'isActive'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $admin = $this->checkAdminAuth();
        if (!$admin instanceof \App\Models\Admin) return $admin;

        $cssSections = $this->qrCodeService->getCssSections();
        $currentAcademicYear = date('Y') . '-' . (date('Y') + 1);

        return view('admin.qr-codes.create', compact('admin', 'cssSections', 'currentAcademicYear'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $admin = $this->checkAdminAuth();
        if (!$admin instanceof \App\Models\Admin) return $admin;

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'target_url' => 'required|url',
            'format' => 'required|in:png,svg',
            'size' => 'required|integer|min:100|max:1000',
            'foreground_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'background_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'track' => 'required|string|max:50',
            'grade_level' => 'nullable|in:11,12',
            'section' => 'nullable|string|max:20',
            'academic_year' => 'required|string|max:20',
            'semester' => 'nullable|string|max:20',
            'version' => 'required|string|max:20',
            'expires_at' => 'nullable|date|after:now',
        ]);

        try {
            $qrCodeData = $request->all();
            $qrCodeData['created_by'] = $admin->username;

            $qrCode = $this->qrCodeService->generateQrCode($qrCodeData);

            // Log QR code creation
            \App\Models\AuditLog::create([
                'admin_id' => $admin->id,
                'action' => 'create_qr_code',
                'description' => 'Created new QR code: ' . $qrCode->name,
                'ip_address' => $request->ip(),
                'new_values' => [
                    'name' => $qrCode->name,
                    'track' => $qrCode->track,
                    'grade_level' => $qrCode->grade_level,
                    'section' => $qrCode->section
                ],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'QR Code generated successfully!',
                'qr_code' => $qrCode,
                'redirect' => route('admin.qr-codes.index')
            ]);

        } catch (\Exception $e) {
            Log::error('QR Code creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate QR Code: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $admin = $this->checkAdminAuth();
        if (!$admin instanceof \App\Models\Admin) return $admin;

        $qrCode = QrCode::findOrFail($id);

        // Log QR code viewing
        \App\Models\AuditLog::create([
            'admin_id' => $admin->id,
            'action' => 'view_qr_code',
            'description' => 'Viewed QR code details: ' . $qrCode->name,
            'ip_address' => request()->ip(),
            'new_values' => ['qr_code_id' => $qrCode->id],
        ]);

        return view('admin.qr-codes.show', compact('admin', 'qrCode'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $admin = $this->checkAdminAuth();
        if (!$admin instanceof \App\Models\Admin) return $admin;

        $qrCode = QrCode::findOrFail($id);
        $cssSections = $this->qrCodeService->getCssSections();

        return view('admin.qr-codes.edit', compact('admin', 'qrCode', 'cssSections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $admin = $this->checkAdminAuth();
        if (!$admin instanceof \App\Models\Admin) return $admin;

        $qrCode = QrCode::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'target_url' => 'required|url',
            'format' => 'required|in:png,svg',
            'size' => 'required|integer|min:100|max:1000',
            'foreground_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'background_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'track' => 'required|string|max:50',
            'grade_level' => 'nullable|in:11,12',
            'section' => 'nullable|string|max:20',
            'academic_year' => 'required|string|max:20',
            'semester' => 'nullable|string|max:20',
            'version' => 'required|string|max:20',
            'expires_at' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        try {
            $oldValues = $qrCode->toArray();
            $qrCode->update($request->all());

            // Regenerate file if format, size, or colors changed
            if ($request->format !== $oldValues['format'] ||
                $request->size !== $oldValues['size'] ||
                $request->foreground_color !== $oldValues['foreground_color'] ||
                $request->background_color !== $oldValues['background_color']) {
                $this->qrCodeService->regenerateFile($qrCode);
            }

            // Log QR code update
            \App\Models\AuditLog::create([
                'admin_id' => $admin->id,
                'action' => 'update_qr_code',
                'description' => 'Updated QR code: ' . $qrCode->name,
                'ip_address' => $request->ip(),
                'old_values' => $oldValues,
                'new_values' => $qrCode->toArray(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'QR Code updated successfully!',
                'qr_code' => $qrCode,
            ]);

        } catch (\Exception $e) {
            Log::error('QR Code update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update QR Code: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $admin = $this->checkAdminAuth();
        if (!$admin instanceof \App\Models\Admin) return $admin;

        try {
            $qrCode = QrCode::findOrFail($id);

            // Delete file from storage
            if ($qrCode->file_path) {
                Storage::disk('public')->delete($qrCode->file_path);
            }

            // Log deletion
            \App\Models\AuditLog::create([
                'admin_id' => $admin->id,
                'action' => 'delete_qr_code',
                'description' => 'Deleted QR code: ' . $qrCode->name,
                'ip_address' => request()->ip(),
                'old_values' => $qrCode->toArray(),
            ]);

            $qrCode->delete();

            return response()->json([
                'success' => true,
                'message' => 'QR Code deleted successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('QR Code deletion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete QR Code: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate QR codes in batch for multiple sections.
     */
    public function batchGenerate(Request $request)
    {
        $admin = $this->checkAdminAuth();
        if (!$admin instanceof \App\Models\Admin) return $admin;

        $request->validate([
            'target_url' => 'required|url',
            'format' => 'required|in:png,svg',
            'size' => 'required|integer|min:100|max:1000',
            'foreground_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'background_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'grade_levels' => 'required|array|min:1',
            'grade_levels.*' => 'in:11,12',
            'sections' => 'required|array|min:1',
            'sections.*' => 'string|max:20',
            'academic_year' => 'required|string|max:20',
            'semester' => 'nullable|string|max:20',
            'version' => 'required|string|max:20',
            'expires_at' => 'nullable|date|after:now',
        ]);

        try {
            $config = $request->all();
            $config['created_by'] = $admin->username;

            $qrCodes = $this->qrCodeService->generateBatch($config);

            // Log batch generation
            \App\Models\AuditLog::create([
                'admin_id' => $admin->id,
                'action' => 'batch_generate_qr_codes',
                'description' => 'Generated ' . count($qrCodes) . ' QR codes in batch',
                'ip_address' => $request->ip(),
                'new_values' => [
                    'count' => count($qrCodes),
                    'grade_levels' => $config['grade_levels'],
                    'sections' => $config['sections']
                ],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Successfully generated ' . count($qrCodes) . ' QR codes!',
                'qr_codes' => $qrCodes,
                'count' => count($qrCodes)
            ]);

        } catch (\Exception $e) {
            Log::error('Batch QR Code generation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate QR codes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download QR code file.
     */
    public function download(string $id)
    {
        $admin = $this->checkAdminAuth();
        if (!$admin instanceof \App\Models\Admin) return $admin;

        try {
            $qrCode = QrCode::findOrFail($id);

            if (!$qrCode->file_path || !Storage::disk('public')->exists($qrCode->file_path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR code file not found.'
                ], 404);
            }

            // Extract format from file extension if not directly available
            $fileExtension = pathinfo($qrCode->file_path, PATHINFO_EXTENSION);
            $format = $fileExtension ?: 'png'; // Default to png if extension not found

            // Log download
            \App\Models\AuditLog::create([
                'admin_id' => $admin->id,
                'action' => 'download_qr_code',
                'description' => 'Downloaded QR code file: ' . $qrCode->name,
                'ip_address' => request()->ip(),
                'new_values' => ['qr_code_id' => $qrCode->id],
            ]);

            // Create filename with proper extension
            $filename = $qrCode->name . '.' . $format;

            // Get the full path to the file
            $fullPath = Storage::disk('public')->path($qrCode->file_path);

            // Return download response
            return response()->download($fullPath, $filename);

        } catch (\Exception $e) {
            Log::error('QR Code download failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to download QR code: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show QR code (for public viewing).
     */
    public function showPublic(string $id)
    {
        $qrCode = QrCode::findOrFail($id);

        if (!$qrCode->is_active || $qrCode->is_expired) {
            return response()->json([
                'success' => false,
                'message' => 'This QR code is no longer active.'
            ], 404);
        }

        // Record scan
        $qrCode->recordScan([
            'referer' => request()->headers->get('referer'),
            'type' => 'public_view'
        ]);

        return redirect()->away($qrCode->target_url);
    }

    /**
     * Get QR code statistics.
     */
    public function statistics(Request $request)
    {
        $admin = $this->checkAdminAuth();
        if (!$admin instanceof \App\Models\Admin) return $admin;

        $filters = [
            'track' => $request->get('track'),
            'grade_level' => $request->get('grade_level'),
            'section' => $request->get('section'),
            'is_active' => $request->get('is_active')
        ];

        $stats = $this->qrCodeService->getStatistics($filters);

        return response()->json([
            'success' => true,
            'statistics' => $stats
        ]);
    }

    /**
     * Export QR codes data.
     */
    public function export(Request $request)
    {
        $admin = $this->checkAdminAuth();
        if (!$admin instanceof \App\Models\Admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access. Please login again.'
            ], 401);
        }

        try {
            $filters = [
                'track' => $request->get('track'),
                'grade_level' => $request->get('grade_level'),
                'section' => $request->get('section'),
                'is_active' => $request->get('is_active')
            ];

            $qrCodes = $this->qrCodeService->exportData($filters);

            // Log export
            \App\Models\AuditLog::create([
                'admin_id' => $admin->id,
                'action' => 'export_qr_codes',
                'description' => 'Exported QR codes data',
                'ip_address' => $request->ip(),
                'new_values' => [
                    'count' => is_array($qrCodes) ? count($qrCodes) : $qrCodes->count(),
                    'filters' => $filters
                ],
            ]);

            // Return as JSON for now (could be extended to CSV/Excel)
            return response()->json([
                'success' => true,
                'data' => $qrCodes,
                'count' => is_array($qrCodes) ? count($qrCodes) : $qrCodes->count()
            ]);

        } catch (\Exception $e) {
            Log::error('QR Code export failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to export QR codes: ' . $e->getMessage()
            ], 500);
        }
    }
}

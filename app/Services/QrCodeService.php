<?php

namespace App\Services;

use App\Models\QrCode;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeFacade;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class QrCodeService
{
    /**
     * Generate a QR code and save it to storage.
     *
     * @param array $data
     * @return QrCode
     */
    public function generateQrCode(array $data)
    {
        // Create QR code record
        $qrCode = QrCode::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'target_url' => $data['target_url'],
            'format' => $data['format'] ?? 'png',
            'size' => $data['size'] ?? 300,
            'foreground_color' => $data['foreground_color'] ?? '#000000',
            'background_color' => $data['background_color'] ?? '#FFFFFF',
            'track' => $data['track'] ?? 'CSS',
            'grade_level' => $data['grade_level'] ?? null,
            'section' => $data['section'] ?? null,
            'academic_year' => $data['academic_year'] ?? null,
            'semester' => $data['semester'] ?? null,
            'version' => $data['version'] ?? '1.0',
            'expires_at' => $data['expires_at'] ?? null,
            'custom_options' => $data['custom_options'] ?? null,
            'created_by' => $data['created_by'] ?? null,
        ]);

        // Generate and save the QR code file
        $this->saveQrCodeFile($qrCode);

        return $qrCode;
    }

    /**
     * Generate and save QR code file.
     *
     * @param QrCode $qrCode
     * @return string
     */
    public function saveQrCodeFile(QrCode $qrCode)
    {
        // Create the QR code
        $qr = QrCodeFacade::size($qrCode->size)
            ->color($this->hexToRgb($qrCode->foreground_color))
            ->backgroundColor($this->hexToRgb($qrCode->background_color))
            ->margin(10)
            ->format($qrCode->format);

        // Apply custom options if provided
        if ($qrCode->custom_options) {
            $qr = $this->applyCustomOptions($qr, $qrCode->custom_options);
        }

        // Generate the QR code
        $qrData = $qr->generate($qrCode->target_url);

        // Determine file path
        $fileName = $this->generateFileName($qrCode);
        $directory = 'qr-codes/' . date('Y/m');

        // Save to storage
        if ($qrCode->format === 'svg') {
            Storage::disk('public')->put($directory . '/' . $fileName, $qrData);
        } else {
            // For PNG, we need to save binary data
            Storage::disk('public')->put($directory . '/' . $fileName, $qrData);
        }

        // Update QR code with file path
        $qrCode->update([
            'file_path' => $directory . '/' . $fileName
        ]);

        return $directory . '/' . $fileName;
    }

    /**
     * Generate multiple QR codes for batch creation.
     *
     * @param array $config
     * @return array
     */
    public function generateBatch(array $config)
    {
        $qrCodes = [];
        $sections = $config['sections'] ?? [];
        $gradeLevels = $config['grade_levels'] ?? [];
        $customizations = $config['customizations'] ?? [];

        foreach ($gradeLevels as $gradeLevel) {
            foreach ($sections as $section) {
                $data = [
                    'name' => "CSS Grade {$gradeLevel} Section {$section}",
                    'description' => "QR code for CSS Grade {$gradeLevel}, Section {$section}",
                    'target_url' => $config['target_url'],
                    'format' => $config['format'] ?? 'png',
                    'size' => $config['size'] ?? 300,
                    'foreground_color' => $config['foreground_color'] ?? '#000000',
                    'background_color' => $config['background_color'] ?? '#FFFFFF',
                    'track' => 'CSS',
                    'grade_level' => $gradeLevel,
                    'section' => $section,
                    'academic_year' => $config['academic_year'] ?? date('Y') . '-' . (date('Y') + 1),
                    'semester' => $config['semester'] ?? null,
                    'version' => $config['version'] ?? '1.0',
                    'expires_at' => $config['expires_at'] ?? null,
                    'custom_options' => $customizations,
                    'created_by' => $config['created_by'] ?? null,
                ];

                $qrCodes[] = $this->generateQrCode($data);
            }
        }

        return $qrCodes;
    }

    /**
     * Regenerate QR code file.
     *
     * @param QrCode $qrCode
     * @return void
     */
    public function regenerateFile(QrCode $qrCode)
    {
        // Delete existing file
        if ($qrCode->file_path) {
            Storage::disk('public')->delete($qrCode->file_path);
        }

        // Generate new file
        $this->saveQrCodeFile($qrCode);
    }

    /**
     * Get QR code statistics.
     *
     * @param array $filters
     * @return array
     */
    public function getStatistics(array $filters = [])
    {
        $query = QrCode::query();

        if (isset($filters['track'])) {
            $query->where('track', $filters['track']);
        }

        if (isset($filters['grade_level'])) {
            $query->where('grade_level', $filters['grade_level']);
        }

        if (isset($filters['section'])) {
            $query->where('section', $filters['section']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        $totalScans = $query->sum('scan_count');
        $totalQrCodes = $query->count();
        $activeQrCodes = $query->where('is_active', true)->count();

        return [
            'total_qr_codes' => $totalQrCodes,
            'active_qr_codes' => $activeQrCodes,
            'total_scans' => $totalScans,
            'average_scans_per_qr' => $totalQrCodes > 0 ? round($totalScans / $totalQrCodes, 2) : 0,
        ];
    }

    /**
     * Apply custom options to QR code.
     *
     * @param mixed $qr
     * @param array $options
     * @return mixed
     */
    private function applyCustomOptions($qr, array $options)
    {
        if (isset($options['eye_color'])) {
            $qr->eyeColor(0, $this->hexToRgb($options['eye_color'][0]), $this->hexToRgb($options['eye_color'][1]), $this->hexToRgb($options['eye_color'][2]));
        }

        if (isset($options['gradient'])) {
            $gradientColors = array_map([$this, 'hexToRgb'], $options['gradient']);
            $qr->gradient(...$gradientColors);
        }

        if (isset($options['border'])) {
            $qr->border($options['border']);
        }

        if (isset($options['style'])) {
            $qr->style($options['style']);
        }

        return $qr;
    }

    /**
     * Generate unique file name for QR code.
     *
     * @param QrCode $qrCode
     * @return string
     */
    private function generateFileName(QrCode $qrCode)
    {
        $baseName = Str::slug($qrCode->name);
        $timestamp = now()->format('Y-m-d_H-i-s');
        $random = Str::random(6);

        return "{$baseName}_{$timestamp}_{$random}.{$qrCode->format}";
    }

    /**
     * Convert hex color to RGB array.
     *
     * @param string $hex
     * @return array
     */
    private function hexToRgb($hex)
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) == 3) {
            $hex = str_repeat(substr($hex, 0, 1), 2) .
                   str_repeat(substr($hex, 1, 1), 2) .
                   str_repeat(substr($hex, 2, 1), 2);
        }

        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2))
        ];
    }

    /**
     * Get CSS sections from existing users or predefined list.
     *
     * @return array
     */
    public function getCssSections()
    {
        // Get sections from existing users
        $existingSections = \App\Models\User::where('role', 'student')
            ->where('track', 'CSS')
            ->distinct()
            ->pluck('section')
            ->sort()
            ->values()
            ->toArray();

        // If no existing sections, provide default ones
        if (empty($existingSections)) {
            return ['C11a', 'C11b', 'C11c', 'C12a', 'C12b', 'C12c'];
        }

        return $existingSections;
    }

    /**
     * Export QR codes data for printing or download.
     *
     * @param array $filters
     * @return array
     */
    public function exportData(array $filters = [])
    {
        $query = QrCode::query()->with(['scan_analytics']);

        if (isset($filters['track'])) {
            $query->where('track', $filters['track']);
        }

        if (isset($filters['grade_level'])) {
            $query->where('grade_level', $filters['grade_level']);
        }

        if (isset($filters['section'])) {
            $query->where('section', $filters['section']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->orderBy('track')
                    ->orderBy('grade_level')
                    ->orderBy('section')
                    ->get();
    }
}

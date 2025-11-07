<?php

namespace Tests\Unit\Models;

use App\Models\QrCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class QrCodeTest extends TestCase
{
    use RefreshDatabase;

    public function test_qr_code_factory_creates_qr_code()
    {
        $qrCode = QrCode::factory()->create();

        $this->assertDatabaseHas('qr_codes', [
            'id' => $qrCode->id,
            'name' => $qrCode->name,
        ]);
    }

    public function test_qr_code_can_be_inactive()
    {
        $qrCode = QrCode::factory()->inactive()->create();

        $this->assertFalse($qrCode->is_active);
    }

    public function test_qr_code_can_be_expired()
    {
        $qrCode = QrCode::factory()->expired()->create();

        $this->assertTrue($qrCode->is_expired);
        $this->assertFalse($qrCode->is_active);
    }

    public function test_qr_code_can_be_scanned()
    {
        $qrCode = QrCode::factory()->scanned(50)->create();

        $this->assertEquals(50, $qrCode->scan_count);
        $this->assertNotEmpty($qrCode->scan_analytics);
    }

    public function test_qr_code_scan_analytics_limited_to_100()
    {
        $qrCode = QrCode::factory()->scanned(150)->create();

        $this->assertEquals(150, $qrCode->scan_count);
        $this->assertCount(100, $qrCode->scan_analytics);
    }

    public function test_qr_code_has_file_url()
    {
        Storage::fake('public');
        $filePath = 'qr-codes/test.png';
        Storage::disk('public')->put($filePath, 'test content');

        $qrCode = QrCode::factory()->create([
            'file_path' => $filePath,
        ]);

        $this->assertNotNull($qrCode->file_url);
        $this->assertStringContainsString($filePath, $qrCode->file_url);
    }

    public function test_qr_code_file_exists_check()
    {
        Storage::fake('public');
        $filePath = 'qr-codes/test.png';

        $qrCode = QrCode::factory()->create([
            'file_path' => $filePath,
        ]);

        $this->assertFalse($qrCode->fileExists());

        Storage::disk('public')->put($filePath, 'test content');
        $this->assertTrue($qrCode->fileExists());
    }

    public function test_qr_code_auto_generates_target_url()
    {
        $qrCode = QrCode::factory()->create([
            'target_url' => null,
        ]);

        $this->assertNotNull($qrCode->target_url);
    }

    public function test_qr_code_auto_sets_academic_year()
    {
        $qrCode = QrCode::factory()->create([
            'academic_year' => null,
        ]);

        $currentYear = date('Y');
        $expectedYear = $currentYear . '-' . ($currentYear + 1);
        $this->assertEquals($expectedYear, $qrCode->academic_year);
    }

    public function test_qr_code_records_scan()
    {
        $qrCode = QrCode::factory()->create([
            'scan_count' => 0,
            'scan_analytics' => [],
        ]);

        $qrCode->recordScan(['device' => 'mobile']);

        $this->assertEquals(1, $qrCode->scan_count);
        $this->assertCount(1, $qrCode->scan_analytics);
        $this->assertEquals('mobile', $qrCode->scan_analytics[0]['device']);
    }

    public function test_qr_code_scopes()
    {
        // Create QR codes with explicit tracks to avoid random track assignment
        QrCode::factory()->active()->create(['track' => 'GAS']); // First active with different track
        QrCode::factory()->inactive()->create(['track' => 'ABM']); // Inactive
        QrCode::factory()->active()->create(['track' => 'CSS']); // Active with CSS track
        QrCode::factory()->active()->create(['track' => 'STEM']); // Another active with different track

        $this->assertCount(3, QrCode::active()->get()); // 3 active QR codes
        $this->assertCount(1, QrCode::byTrack('CSS')->get()); // Only 1 with CSS track
    }

    public function test_qr_code_casts()
    {
        $qrCode = QrCode::factory()->create([
            'scan_analytics' => ['test' => 'data'],
            'custom_options' => ['option' => 'value'],
            'expires_at' => now()->addDays(30),
            'is_active' => true,
        ]);

        $this->assertIsArray($qrCode->scan_analytics);
        $this->assertIsArray($qrCode->custom_options);
        $this->assertInstanceOf(\Carbon\Carbon::class, $qrCode->expires_at);
        $this->assertIsBool($qrCode->is_active);
    }
}


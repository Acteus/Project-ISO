<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('qr_codes', function (Blueprint $table) {
            $table->id();

            // Basic Information
            $table->string('name')->comment('Human-readable name for the QR code');
            $table->string('description')->nullable()->comment('Optional description');

            // Target URL and Path
            $table->string('target_url')->comment('URL that QR code should point to');
            $table->string('file_path')->nullable()->comment('Path to stored QR code file');

            // QR Code Properties
            $table->enum('format', ['png', 'svg'])->default('png');
            $table->integer('size')->default(300)->comment('QR code size in pixels');
            $table->string('foreground_color')->default('#000000')->comment('QR code foreground color');
            $table->string('background_color')->default('#FFFFFF')->comment('QR code background color');

            // Targeting and Organization
            $table->string('track')->default('CSS')->comment('Academic track');
            $table->string('grade_level')->nullable()->comment('Target grade level (11, 12)');
            $table->string('section')->nullable()->comment('Specific section');
            $table->string('academic_year')->nullable()->comment('Academic year');
            $table->string('semester')->nullable()->comment('Semester');

            // Usage Tracking and Analytics
            $table->integer('scan_count')->default(0)->comment('Number of times QR code was scanned');
            $table->json('scan_analytics')->nullable()->comment('JSON data for scan analytics');

            // Version Control and Expiration
            $table->string('version')->default('1.0')->comment('Version of the QR code');
            $table->timestamp('expires_at')->nullable()->comment('Expiration date');
            $table->boolean('is_active')->default(true)->comment('Whether this QR code is currently active');

            // Metadata
            $table->json('custom_options')->nullable()->comment('Additional customization options');
            $table->string('created_by')->nullable()->comment('Admin user who created this QR code');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_codes');
    }
};

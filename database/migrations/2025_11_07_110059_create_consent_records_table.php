<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Creates consent_records table for GDPR & ISO 27001 compliance
     * Tracks explicit consent with audit trail
     */
    public function up(): void
    {
        Schema::create('consent_records', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->nullable()->index(); // Encrypted student ID
            $table->string('ip_address')->nullable();
            $table->boolean('consent_given')->default(false);
            $table->string('consent_purpose')->default('survey_response'); // Purpose of consent
            $table->string('consent_version')->default('1.0'); // Version of consent terms
            $table->timestamp('expires_at')->nullable(); // When consent expires
            $table->timestamp('revoked_at')->nullable(); // When consent was revoked
            $table->json('metadata')->nullable(); // Additional context
            $table->timestamps();

            // Indexes for performance
            $table->index(['student_id', 'consent_purpose']);
            $table->index(['consent_given', 'expires_at']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consent_records');
    }
};

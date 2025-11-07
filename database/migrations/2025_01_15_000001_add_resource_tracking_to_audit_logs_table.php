<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds resource tracking fields for ISO 21001 Clause 8.2.4 (Traceability)
     */
    public function up(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            // Add resource tracking for ISO 21001 traceability
            $table->string('resource_type')->nullable()->after('action');
            $table->unsignedBigInteger('resource_id')->nullable()->after('resource_type');
            $table->json('metadata')->nullable()->after('new_values');
            
            // Add indexes for efficient querying
            $table->index(['resource_type', 'resource_id'], 'audit_logs_resource_index');
            $table->index(['action', 'created_at'], 'audit_logs_action_time_index');
            $table->index('ip_address', 'audit_logs_ip_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropIndex('audit_logs_resource_index');
            $table->dropIndex('audit_logs_action_time_index');
            $table->dropIndex('audit_logs_ip_index');
            
            $table->dropColumn(['resource_type', 'resource_id', 'metadata']);
        });
    }
};



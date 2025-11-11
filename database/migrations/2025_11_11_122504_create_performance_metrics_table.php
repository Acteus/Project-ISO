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
        Schema::create('performance_metrics', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'ai_service' or 'analytics_query'
            $table->string('category'); // Service name or 'analytics'
            $table->string('operation'); // Endpoint or query type
            $table->float('duration_ms'); // Duration in milliseconds
            $table->boolean('success')->default(true);
            $table->text('error')->nullable();
            $table->integer('rows_affected')->nullable(); // For queries
            $table->text('query')->nullable(); // SQL query (truncated)
            $table->text('metadata')->nullable(); // JSON metadata
            $table->timestamp('timestamp');
            $table->timestamps();

            // Indexes for performance
            $table->index(['type', 'timestamp']);
            $table->index(['category', 'timestamp']);
            $table->index(['operation', 'timestamp']);
            $table->index('success');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_metrics');
    }
};

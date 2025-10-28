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
        Schema::create('weekly_metrics', function (Blueprint $table) {
            $table->id();
            $table->date('week_start_date'); // Start date of the week (Monday)
            $table->date('week_end_date'); // End date of the week (Sunday)
            $table->integer('year'); // Year for easy querying
            $table->integer('week_number'); // ISO week number

            // Response counts
            $table->integer('total_responses')->default(0);
            $table->integer('new_responses')->default(0); // Responses from this week

            // ISO 21001 Quality Indices (weekly averages)
            $table->decimal('learner_needs_index', 3, 2)->nullable();
            $table->decimal('satisfaction_score', 3, 2)->nullable();
            $table->decimal('success_index', 3, 2)->nullable();
            $table->decimal('safety_index', 3, 2)->nullable();
            $table->decimal('wellbeing_index', 3, 2)->nullable();
            $table->decimal('overall_satisfaction', 3, 2)->nullable();

            // Compliance metrics
            $table->decimal('compliance_score', 3, 2)->nullable();
            $table->decimal('compliance_percentage', 5, 2)->nullable();
            $table->string('risk_level')->nullable(); // Low, Medium, High

            // Demographic breakdowns (JSON for flexibility)
            $table->json('responses_by_track')->nullable();
            $table->json('responses_by_grade')->nullable();
            $table->json('responses_by_gender')->nullable();

            // Trend indicators (compared to previous week)
            $table->decimal('satisfaction_trend', 4, 2)->nullable(); // Percentage change
            $table->decimal('compliance_trend', 4, 2)->nullable(); // Percentage change
            $table->decimal('response_trend', 4, 2)->nullable(); // Percentage change

            // Target/goal tracking
            $table->decimal('target_satisfaction', 3, 2)->default(4.0);
            $table->decimal('target_compliance', 5, 2)->default(80.0);
            $table->integer('target_responses')->default(50);

            // Achievement flags
            $table->boolean('satisfaction_target_met')->default(false);
            $table->boolean('compliance_target_met')->default(false);
            $table->boolean('response_target_met')->default(false);

            // Additional metadata
            $table->json('key_insights')->nullable(); // AI-generated insights
            $table->json('recommendations')->nullable(); // Action items

            $table->timestamps();

            // Indexes for performance
            $table->index(['year', 'week_number']);
            $table->index('week_start_date');
            $table->unique(['year', 'week_number']); // One record per week
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_metrics');
    }
};

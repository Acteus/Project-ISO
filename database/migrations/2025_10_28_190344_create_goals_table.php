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
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Goal name (e.g., "Improve Satisfaction Score")
            $table->text('description')->nullable(); // Detailed description
            $table->string('metric_type'); // Type of metric (satisfaction, compliance, responses, etc.)
            $table->decimal('target_value', 5, 2); // Target value to achieve
            $table->decimal('current_value', 5, 2)->nullable(); // Current value
            $table->date('target_date')->nullable(); // When to achieve the goal
            $table->string('status')->default('active'); // active, achieved, expired, cancelled
            $table->integer('priority')->default(1); // 1=low, 2=medium, 3=high, 4=critical
            $table->json('progress_history')->nullable(); // Track progress over time
            $table->text('notes')->nullable(); // Additional notes
            $table->timestamps();

            // Indexes
            $table->index(['metric_type', 'status']);
            $table->index('target_date');
            $table->index('priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goals');
    }
};

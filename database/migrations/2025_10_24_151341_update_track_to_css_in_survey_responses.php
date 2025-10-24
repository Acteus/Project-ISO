<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Updates the track field from STEM to CSS (Computer System Servicing)
     * to reflect the correct student track for this ISO 21001 system.
     */
    public function up(): void
    {
        // For SQLite, we need to recreate the table with the new enum constraint
        // First, get the database connection type
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            // SQLite: First drop the index, then drop and recreate the track column
            Schema::table('survey_responses', function (Blueprint $table) {
                // Drop the composite index that includes track
                $table->dropIndex(['track', 'grade_level']);
            });

            Schema::table('survey_responses', function (Blueprint $table) {
                $table->dropColumn('track');
            });

            Schema::table('survey_responses', function (Blueprint $table) {
                $table->enum('track', ['CSS'])->default('CSS')->after('student_id');
            });

            // Recreate the index
            Schema::table('survey_responses', function (Blueprint $table) {
                $table->index(['track', 'grade_level']);
            });
        } else {
            // For MySQL/PostgreSQL, we can use ALTER
            DB::statement("ALTER TABLE survey_responses MODIFY COLUMN track ENUM('CSS') DEFAULT 'CSS'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse back to STEM
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            Schema::table('survey_responses', function (Blueprint $table) {
                $table->dropIndex(['track', 'grade_level']);
            });

            Schema::table('survey_responses', function (Blueprint $table) {
                $table->dropColumn('track');
            });

            Schema::table('survey_responses', function (Blueprint $table) {
                $table->enum('track', ['STEM'])->default('STEM')->after('student_id');
            });

            Schema::table('survey_responses', function (Blueprint $table) {
                $table->index(['track', 'grade_level']);
            });
        } else {
            DB::statement("ALTER TABLE survey_responses MODIFY COLUMN track ENUM('STEM') DEFAULT 'STEM'");
        }
    }
};

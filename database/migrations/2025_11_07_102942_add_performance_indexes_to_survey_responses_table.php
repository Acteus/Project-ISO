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
     * Adds performance indexes on frequently filtered columns:
     * - track, grade_level, academic_year, semester (for filtering)
     * - created_at (for date range queries and sorting)
     * - Composite indexes for common filter combinations
     */
    public function up(): void
    {
        Schema::table('survey_responses', function (Blueprint $table) {
            // Check if indexes already exist before adding
            $driver = DB::connection()->getDriverName();

            if ($driver === 'sqlite') {
                // SQLite: Check and add indexes if they don't exist
                // Note: SQLite doesn't support IF NOT EXISTS in Laravel migrations
                // We'll use try-catch or check via raw SQL
                try {
                    // Individual column indexes for frequently filtered columns
                    if (!$this->indexExists('survey_responses', 'survey_responses_track_index')) {
                        $table->index('track', 'survey_responses_track_index');
                    }
                    if (!$this->indexExists('survey_responses', 'survey_responses_grade_level_index')) {
                        $table->index('grade_level', 'survey_responses_grade_level_index');
                    }
                    if (!$this->indexExists('survey_responses', 'survey_responses_academic_year_index')) {
                        $table->index('academic_year', 'survey_responses_academic_year_index');
                    }
                    if (!$this->indexExists('survey_responses', 'survey_responses_semester_index')) {
                        $table->index('semester', 'survey_responses_semester_index');
                    }
                    if (!$this->indexExists('survey_responses', 'survey_responses_created_at_index')) {
                        $table->index('created_at', 'survey_responses_created_at_index');
                    }

                    // Composite indexes for common filter combinations
                    // Check if indexes already exist by column names (Laravel's Schema::hasIndex method)
                    if (!Schema::hasIndex('survey_responses', ['track', 'grade_level'])) {
                        $table->index(['track', 'grade_level'], 'survey_responses_track_grade_level_index');
                    }
                    if (!Schema::hasIndex('survey_responses', ['academic_year', 'semester'])) {
                        $table->index(['academic_year', 'semester'], 'survey_responses_academic_year_semester_index');
                    }
                    // New composite index for date range queries with track filtering
                    if (!Schema::hasIndex('survey_responses', ['created_at', 'track'])) {
                        $table->index(['created_at', 'track'], 'survey_responses_created_at_track_index');
                    }
                } catch (\Exception $e) {
                    // Index might already exist, continue
                }
            } else {
                // MySQL/PostgreSQL: Use conditional index creation
                // Individual column indexes
                $indexes = [
                    'track' => 'survey_responses_track_index',
                    'grade_level' => 'survey_responses_grade_level_index',
                    'academic_year' => 'survey_responses_academic_year_index',
                    'semester' => 'survey_responses_semester_index',
                    'created_at' => 'survey_responses_created_at_index',
                ];

                foreach ($indexes as $column => $indexName) {
                    if (!$this->indexExists('survey_responses', $indexName)) {
                        $table->index($column, $indexName);
                    }
                }

                // Composite indexes
                // Check if indexes already exist by column names (Laravel's Schema::hasIndex method)
                // Some indexes may have been created in previous migrations
                if (!Schema::hasIndex('survey_responses', ['track', 'grade_level'])) {
                    $table->index(['track', 'grade_level'], 'survey_responses_track_grade_level_index');
                }

                if (!Schema::hasIndex('survey_responses', ['academic_year', 'semester'])) {
                    $table->index(['academic_year', 'semester'], 'survey_responses_academic_year_semester_index');
                }

                // New composite index for date range queries with track filtering
                if (!Schema::hasIndex('survey_responses', ['created_at', 'track'])) {
                    $table->index(['created_at', 'track'], 'survey_responses_created_at_track_index');
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_responses', function (Blueprint $table) {
            $indexes = [
                'survey_responses_track_index',
                'survey_responses_grade_level_index',
                'survey_responses_academic_year_index',
                'survey_responses_semester_index',
                'survey_responses_created_at_index',
                'survey_responses_track_grade_level_index',
                'survey_responses_academic_year_semester_index',
                'survey_responses_created_at_track_index',
            ];

            foreach ($indexes as $indexName) {
                if ($this->indexExists('survey_responses', $indexName)) {
                    $table->dropIndex($indexName);
                }
            }
        });
    }

    /**
     * Check if an index exists
     * Handles both explicit index names and Laravel auto-generated names
     */
    private function indexExists(string $table, string $index): bool
    {
        $driver = DB::connection()->getDriverName();

        try {
            if ($driver === 'sqlite') {
                $result = DB::select("SELECT name FROM sqlite_master WHERE type='index' AND name=?", [$index]);
                return !empty($result);
            } else {
                // MySQL/PostgreSQL: Check information_schema
                $database = DB::connection()->getDatabaseName();
                $result = DB::select(
                    "SELECT COUNT(*) as count FROM information_schema.STATISTICS
                     WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND INDEX_NAME = ?",
                    [$database, $table, $index]
                );
                return isset($result[0]) && $result[0]->count > 0;
            }
        } catch (\Exception $e) {
            // If there's an error checking, assume index doesn't exist to be safe
            // This prevents migration failures
            return false;
        }
    }
};

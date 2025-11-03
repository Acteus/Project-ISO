<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get the actual foreign key constraint name
        $foreignKeys = DB::select(
            "SELECT CONSTRAINT_NAME
             FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = DATABASE()
             AND TABLE_NAME = 'audit_logs'
             AND COLUMN_NAME = 'admin_id'
             AND REFERENCED_TABLE_NAME IS NOT NULL"
        );

        Schema::table('audit_logs', function (Blueprint $table) use ($foreignKeys) {
            // Drop foreign key if it exists
            if (!empty($foreignKeys)) {
                DB::statement('ALTER TABLE audit_logs DROP FOREIGN KEY ' . $foreignKeys[0]->CONSTRAINT_NAME);
            }

            // Drop index if it exists
            $indexExists = DB::select(
                "SELECT INDEX_NAME
                 FROM information_schema.STATISTICS
                 WHERE TABLE_SCHEMA = DATABASE()
                 AND TABLE_NAME = 'audit_logs'
                 AND INDEX_NAME = 'audit_logs_admin_id_created_at_index'"
            );

            if (!empty($indexExists)) {
                $table->dropIndex('audit_logs_admin_id_created_at_index');
            }

            // Drop column
            $table->dropColumn('admin_id');
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex(['user_id', 'created_at']);
            $table->dropColumn('user_id');
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('admin_id')->nullable()->after('id');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('set null');
            $table->index(['admin_id', 'created_at']);
        });
    }
};

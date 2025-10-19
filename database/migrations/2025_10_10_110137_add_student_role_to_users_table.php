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
        // For SQLite, we need to recreate the table to modify enum
        if (DB::getDriverName() === 'sqlite') {
            // Get existing data
            $users = DB::table('users')->get();

            // Drop the role column
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });

            // Add it back with the new enum values
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['admin', 'user', 'student'])->default('user');
            });

            // Update existing users to have appropriate roles based on their data
            foreach ($users as $user) {
                $role = 'user'; // default

                // If they have student fields filled, make them student
                if ($user->year_level || $user->section || $user->student_id) {
                    $role = 'student';
                }

                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['role' => $role]);
            }
        } else {
            // For MySQL/PostgreSQL, use ALTER TABLE
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'user', 'student') DEFAULT 'user'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            // Get existing data
            $users = DB::table('users')->get();

            // Drop the role column
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });

            // Add it back with the original enum values
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['admin', 'user'])->default('user');
            });

            // Restore original roles
            foreach ($users as $user) {
                $role = 'user'; // default for rollback
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['role' => $role]);
            }
        } else {
            // For MySQL/PostgreSQL, revert the enum
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'user') DEFAULT 'user'");
        }
    }
};

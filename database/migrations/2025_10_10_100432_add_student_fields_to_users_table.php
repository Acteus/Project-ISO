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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'student_id')) {
                $table->string('student_id')->unique()->nullable()->after('id');
            }
            if (!Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name')->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'last_name')) {
                $table->string('last_name')->nullable()->after('first_name');
            }
            if (!Schema::hasColumn('users', 'year_level')) {
                $table->integer('year_level')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'section')) {
                $table->string('section')->nullable()->after('year_level');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('users', 'student_id')) {
                $columns[] = 'student_id';
            }
            if (Schema::hasColumn('users', 'first_name')) {
                $columns[] = 'first_name';
            }
            if (Schema::hasColumn('users', 'last_name')) {
                $columns[] = 'last_name';
            }
            if (Schema::hasColumn('users', 'year_level')) {
                $columns[] = 'year_level';
            }
            if (Schema::hasColumn('users', 'section')) {
                $columns[] = 'section';
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};

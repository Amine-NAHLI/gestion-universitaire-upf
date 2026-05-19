<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Safety migration: remove plain_password column from users table if it exists.
 * The original migration (2026_05_18_135243) was empty (no column was ever added),
 * but this ensures no plain-text password is ever stored in the database.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('users', 'plain_password')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('plain_password');
            });
        }
    }

    public function down(): void
    {
        // We do NOT restore a plain_password column — storing plain passwords is a security risk.
    }
};

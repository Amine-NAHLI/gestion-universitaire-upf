<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications_app', function (Blueprint $table) {
            $table->string('type')->default('info')->change();
        });
    }

    public function down(): void
    {
        Schema::table('notifications_app', function (Blueprint $table) {
            $table->enum('type', ['info', 'success', 'warning', 'error'])->default('info')->change();
        });
    }
};

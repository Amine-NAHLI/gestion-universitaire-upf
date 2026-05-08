<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications_app', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('titre');
            $table->text('message');
            $table->enum('type', ['info', 'success', 'warning', 'error'])->default('info');
            $table->string('lien')->nullable();
            $table->boolean('lue')->default(false);
            $table->timestamp('date_lecture')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('lue');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications_app');
    }
};

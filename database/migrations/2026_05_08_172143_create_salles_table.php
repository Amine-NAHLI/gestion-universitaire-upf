<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salles', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->unique();
            $table->integer('capacite')->default(30);
            $table->enum('type', ['cours', 'td', 'tp', 'amphi'])->default('cours');
            $table->text('equipements')->nullable();
            $table->boolean('is_disponible')->default(true);
            $table->timestamps();

            $table->index('type');
            $table->index('is_disponible');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salles');
    }
};

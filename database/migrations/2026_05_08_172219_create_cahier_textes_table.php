<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cahier_textes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seance_id')->unique()->constrained()->onDelete('cascade');
            $table->text('objectif');
            $table->text('contenu')->nullable();
            $table->enum('nature', ['cours', 'td', 'tp'])->default('cours');
            $table->timestamps();

            $table->index('seance_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cahier_textes');
    }
};

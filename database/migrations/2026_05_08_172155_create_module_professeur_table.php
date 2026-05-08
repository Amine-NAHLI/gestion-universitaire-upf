<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('module_professeur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->foreignId('professeur_id')->constrained()->onDelete('cascade');
            $table->string('annee_universitaire')->default('2025-2026');
            $table->timestamps();

            $table->unique(['module_id', 'professeur_id', 'annee_universitaire'], 'module_prof_annee_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('module_professeur');
    }
};

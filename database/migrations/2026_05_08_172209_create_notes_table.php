<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained()->onDelete('cascade');
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->decimal('cc1', 5, 2)->nullable();
            $table->decimal('cc2', 5, 2)->nullable();
            $table->decimal('examen', 5, 2)->nullable();
            $table->decimal('note_finale', 5, 2)->nullable();
            $table->string('annee_universitaire')->default('2025-2026');
            $table->timestamps();

            $table->unique(['etudiant_id', 'module_id', 'annee_universitaire']);
            $table->index('etudiant_id');
            $table->index('module_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};

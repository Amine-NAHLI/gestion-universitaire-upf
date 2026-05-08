<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->foreignId('professeur_id')->constrained()->onDelete('cascade');
            $table->foreignId('groupe_id')->constrained()->onDelete('cascade');
            $table->foreignId('salle_id')->nullable()->constrained()->nullOnDelete();
            $table->date('date');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->enum('type', ['cours', 'td', 'tp', 'examen'])->default('cours');
            $table->enum('statut', ['planifiee', 'effectuee', 'annulee'])->default('planifiee');
            $table->timestamps();

            $table->index('date');
            $table->index('statut');
            $table->index(['module_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seances');
    }
};

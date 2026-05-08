<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations_salles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salle_id')->constrained()->onDelete('cascade');
            $table->foreignId('professeur_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->string('motif');
            $table->enum('statut', ['confirmee', 'annulee'])->default('confirmee');
            $table->timestamps();

            $table->index(['date', 'salle_id']);
            $table->index('statut');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations_salles');
    }
};

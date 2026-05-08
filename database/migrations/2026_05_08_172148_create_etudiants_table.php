<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('etudiants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->string('cne')->unique();
            $table->string('matricule')->unique();
            $table->foreignId('groupe_id')->constrained()->onDelete('cascade');
            $table->date('date_inscription');
            $table->enum('statut', ['inscrit', 'suspendu', 'diplome'])->default('inscrit');
            $table->timestamps();

            $table->index('cne');
            $table->index('matricule');
            $table->index('statut');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etudiants');
    }
};

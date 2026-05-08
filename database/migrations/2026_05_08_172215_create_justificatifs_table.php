<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('justificatifs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained()->onDelete('cascade');
            $table->foreignId('absence_id')->constrained()->onDelete('cascade');
            $table->string('fichier');
            $table->text('motif');
            $table->enum('statut', ['en_attente', 'accepte', 'refuse'])->default('en_attente');
            $table->text('motif_refus')->nullable();
            $table->timestamp('date_soumission')->useCurrent();
            $table->foreignId('validateur_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('statut');
            $table->index('etudiant_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('justificatifs');
    }
};

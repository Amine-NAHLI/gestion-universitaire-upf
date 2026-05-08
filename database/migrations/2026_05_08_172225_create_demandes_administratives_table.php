<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demandes_administratives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', [
                'attestation_scolarite',
                'releve_notes',
                'certificat_inscription',
                'attestation_travail',
                'ordre_mission'
            ]);
            $table->json('donnees_supplementaires')->nullable();
            $table->enum('statut', ['en_attente', 'validee', 'refusee'])->default('en_attente');
            $table->text('motif_refus')->nullable();
            $table->string('fichier_pdf')->nullable();
            $table->foreignId('validateur_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('date_validation')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('statut');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demandes_administratives');
    }
};

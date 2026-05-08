<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supports_cours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->foreignId('professeur_id')->constrained()->onDelete('cascade');
            $table->string('titre');
            $table->text('description')->nullable();
            $table->string('fichier');
            $table->string('type_fichier')->nullable();
            $table->integer('taille')->nullable();
            $table->timestamps();

            $table->index('module_id');
            $table->index('professeur_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supports_cours');
    }
};

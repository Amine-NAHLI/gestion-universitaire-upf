<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('professeurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->string('matricule')->unique();
            $table->string('specialite')->nullable();
            $table->enum('grade', ['vacataire', 'assistant', 'maitre_assistant', 'professeur'])->default('assistant');
            $table->date('date_recrutement')->nullable();
            $table->timestamps();

            $table->index('matricule');
            $table->index('grade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('professeurs');
    }
};

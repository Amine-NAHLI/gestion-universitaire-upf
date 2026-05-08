<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained()->onDelete('cascade');
            $table->foreignId('seance_id')->constrained()->onDelete('cascade');
            $table->boolean('justifiee')->default(false);
            $table->timestamp('date_creation')->useCurrent();
            $table->timestamps();

            $table->unique(['etudiant_id', 'seance_id']);
            $table->index('etudiant_id');
            $table->index('justifiee');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absences');
    }
};

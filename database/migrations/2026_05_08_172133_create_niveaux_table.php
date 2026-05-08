<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('niveaux', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->integer('numero');
            $table->foreignId('filiere_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->index('filiere_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('niveaux');
    }
};

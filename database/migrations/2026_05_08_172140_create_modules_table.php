<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->decimal('coefficient', 4, 2)->default(1.00);
            $table->integer('heures_cours')->default(0);
            $table->integer('heures_td')->default(0);
            $table->integer('heures_tp')->default(0);
            $table->foreignId('niveau_id')->constrained()->onDelete('cascade');
            $table->integer('semestre')->default(1);
            $table->timestamps();

            $table->index('code');
            $table->index('niveau_id');
            $table->index('semestre');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};

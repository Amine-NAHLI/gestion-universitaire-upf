<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('annonces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->foreignId('professeur_id')->constrained()->onDelete('cascade');
            $table->string('titre');
            $table->text('contenu');
            $table->boolean('epinglee')->default(false);
            $table->timestamps();

            $table->index('module_id');
            $table->index('epinglee');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('annonces');
    }
};

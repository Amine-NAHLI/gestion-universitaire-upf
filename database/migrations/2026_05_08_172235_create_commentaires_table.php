<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commentaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('annonce_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('contenu');
            $table->timestamps();

            $table->index('annonce_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commentaires');
    }
};

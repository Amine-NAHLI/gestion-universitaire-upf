<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('groupes', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->foreignId('niveau_id')->constrained()->onDelete('cascade');
            $table->integer('effectif_max')->default(40);
            $table->timestamps();

            $table->index('niveau_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('groupes');
    }
};

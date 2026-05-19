<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('document_signatures', function (Blueprint $table) {
            $table->id();
            $table->string('document_id', 64)->unique(); // Public-facing ID (ex: DOC-xxxx)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('document_type'); // releve_notes, attestation_inscription, etc.
            $table->text('data_hash'); // SHA-256 hash of the document data
            $table->text('signature'); // RSA cryptographic signature (base64)
            $table->json('sealed_data'); // The exact data that was signed (for verification display)
            $table->timestamp('issued_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_signatures');
    }
};

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
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            // Barang apa yang diklaim?
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            // Siapa yang mengklaim?
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->text('proof_description'); // Deskripsi bukti
            $table->string('proof_file')->nullable(); // File bukti
            
            $table->enum('status', ['pending', 'verified', 'rejected', 'completed'])->default('pending');
            
            // Siapa petugas yang memproses? (Nullable karena awal submit belum ada yang proses)
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->foreign('verified_by')->references('id')->on('users');
            
            $table->text('verification_notes')->nullable(); // Alasan diterima/tolak
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};

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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            // Relasi ke User (Pelapor)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Relasi ke Kategori
            $table->foreignId('category_id')->constrained();
            
            $table->enum('type', ['lost', 'found']); // Hilang atau Temuan
            $table->string('title');
            $table->text('description');
            $table->date('date'); // Tanggal hilang/ditemukan
            $table->string('location');
            $table->string('image_path')->nullable();
            
            // Status verifikasi (Barang fisik sudah di admin/security belum?)
            $table->boolean('is_verified')->default(false); 
            
            // Status flow barang
            $table->enum('status', ['open', 'claimed', 'returned', 'cancelled'])->default('open');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};

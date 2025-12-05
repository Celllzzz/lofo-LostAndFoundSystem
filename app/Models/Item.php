<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'type',         // lost / found
        'title',
        'description',
        'date',
        'location',
        'image_path',
        'is_verified',
        'status',       // open, claimed, etc
    ];

    // Mengubah tipe data otomatis saat diakses
    protected $casts = [
        'date' => 'date',             // Agar bisa format tanggal ($item->date->format('d M Y'))
        'is_verified' => 'boolean',   // Agar jadi true/false, bukan 1/0
    ];

    // --- RELATIONS ---

    // Barang milik satu user (pelapor)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Barang masuk dalam satu kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Satu barang bisa memiliki banyak ajuan klaim
    public function claims()
    {
        return $this->hasMany(Claim::class);
    }
}
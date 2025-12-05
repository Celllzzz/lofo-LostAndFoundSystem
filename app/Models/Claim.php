<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'user_id',
        'proof_description',
        'proof_file',
        'status',
        'verified_by',
        'verification_notes',
    ];

    // --- RELATIONS ---

    // Klaim tertuju pada satu barang
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // Klaim diajukan oleh satu user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Klaim diverifikasi oleh satu user (Admin/Security)
    // Karena nama kolomnya 'verified_by' bukan 'user_id', kita harus definisikan foreign key-nya
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
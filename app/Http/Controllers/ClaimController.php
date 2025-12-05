<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClaimController extends Controller
{
    // Mahasiswa mengajukan klaim
    public function store(Request $request, Item $item)
    {
        $request->validate([
            'proof_description' => 'required|string',
            'proof_file' => 'nullable|image|max:2048',
        ]);

        // Cek apakah user ini pelapor barangnya sendiri (Opsional: cegah spam)
        if($item->user_id == Auth::id()){
             return back()->with('error', 'Anda tidak bisa mengklaim barang yang anda lapor sendiri sebagai temuan.');
        }

        $path = null;
        if ($request->hasFile('proof_file')) {
            $path = $request->file('proof_file')->store('claims', 'public');
        }

        Claim::create([
            'item_id' => $item->id,
            'user_id' => Auth::id(),
            'proof_description' => $request->proof_description,
            'proof_file' => $path,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Klaim diajukan! Tunggu verifikasi admin.');
    }

    // Admin/Security Menyetujui/Menolak Klaim
    public function updateStatus(Request $request, Claim $claim)
    {
        $request->validate(['status' => 'required|in:verified,rejected']);

        // Update status klaim
        $claim->update([
            'status' => $request->status,
            'verified_by' => Auth::id(),
            'verification_notes' => $request->notes, // Catatan admin
        ]);

        // Jika klaim diterima, otomatis barang jadi 'claimed'
        if ($request->status == 'verified') {
            $claim->item->update(['status' => 'claimed']);
        }

        return back()->with('success', 'Status klaim diperbarui.');
    }
}
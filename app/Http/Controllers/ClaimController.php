<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClaimController extends Controller
{   
    public function index(Request $request)
    {
        $query = Claim::with('item')
                    ->where('user_id', Auth::id());

        // 1. Logic Search (Cari berdasarkan nama barang atau deskripsi bukti)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('proof_description', 'like', "%{$search}%")
                  ->orWhereHas('item', function($q2) use ($search) {
                      $q2->where('title', 'like', "%{$search}%");
                  });
            });
        }

        // 2. Logic Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 3. Logic Pagination & Show Entries
        $perPage = $request->input('per_page', 10);
        
        $claims = $query->latest()
                        ->paginate($perPage)
                        ->withQueryString(); // Agar parameter search tidak hilang saat ganti halaman

        return view('claims.index', compact('claims'));
    }
    
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
    // Pada method updateStatus
    public function updateStatus(Request $request, Claim $claim)
    {
        $request->validate(['status' => 'required|in:verified,rejected,completed']);

        // Jika status 'completed' (Barang diambil)
        if ($request->status == 'completed') {
            $claim->update([
                'status' => 'completed', // Status klaim selesai
                'verified_by' => auth()->id()
            ]);
            
            // Update status barang jadi 'returned' (sudah dikembalikan)
            $claim->item->update(['status' => 'returned']); // Pastikan enum 'returned' ada di database items

            return back()->with('success', 'Serah terima berhasil. Kasus ditutup.');
        }

        // Logika Verified/Rejected biasa
        $claim->update([
            'status' => $request->status,
            'verified_by' => auth()->id(),
            'verification_notes' => $request->notes,
        ]);

        if ($request->status == 'verified') {
            $claim->item->update(['status' => 'claimed']);
        } elseif ($request->status == 'rejected') {
            $claim->item->update(['status' => 'open']); // Buka lagi status barangnya
        }

        return back()->with('success', 'Status klaim diperbarui.');
    }
}
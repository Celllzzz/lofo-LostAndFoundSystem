<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Claim;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Dashboard khusus Admin/Security
    public function dashboard()
    {
        // Barang temuan yang belum diverifikasi
        $unverifiedItems = Item::where('type', 'found')
                                ->where('is_verified', false)
                                ->get();
        
        // Klaim yang masuk (pending)
        $pendingClaims = Claim::with(['item', 'user'])
                              ->where('status', 'pending')
                              ->get();

        return view('admin.dashboard', compact('unverifiedItems', 'pendingClaims'));
    }

    // Action: Verifikasi Barang (Security sudah terima fisik barangnya)
    public function verifyItem(Item $item)
    {
        $item->update(['is_verified' => true]);
        return back()->with('success', 'Barang berhasil diverifikasi & tayang di publik.');
    }
}
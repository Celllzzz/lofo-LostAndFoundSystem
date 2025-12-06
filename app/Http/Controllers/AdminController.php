<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Claim;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        // 1. Barang Temuan Mahasiswa yang belum diverifikasi fisik oleh Satpam
        $unverifiedItems = Item::where('type', 'found')
                                ->where('is_verified', false)
                                ->latest()
                                ->get();

        // 2. Klaim baru yang butuh verifikasi bukti
        $pendingClaims = Claim::where('status', 'pending')
                                ->with(['item', 'user'])
                                ->latest()
                                ->get();

        // 3. Klaim yang sudah disetujui, menunggu barang diambil (Serah Terima)
        $approvedClaims = Claim::where('status', 'verified')
                                ->with(['item', 'user'])
                                ->latest()
                                ->get();

        return view('admin.dashboard', compact('unverifiedItems', 'pendingClaims', 'approvedClaims'));
    }

    // Satpam memverifikasi fisik barang temuan
    public function verifyItem(Item $item)
    {
        $item->update(['is_verified' => true]);
        return back()->with('success', 'Barang berhasil diverifikasi. Sekarang muncul di list publik.');
    }
}
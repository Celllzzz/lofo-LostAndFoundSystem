<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    // Menampilkan Dashboard Barang (Filter: Lost vs Found)
    public function index(Request $request)
    {
        // Ambil data kategori untuk dropdown filter
        $categories = Category::all();

        // Query Dasar (Verified & Open)
        $query = Item::with('category', 'user')
            ->where('status', 'open')     // Status masih aktif
            ->where('is_verified', 1);    // Sudah diverifikasi admin

        // 1. Filter Pencarian (Search)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // 2. Filter Kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // 3. Filter Tipe (Lost/Found) - Jika user klik tombol filter tipe
        if ($request->filled('type') && in_array($request->type, ['lost', 'found'])) {
            $query->where('type', $request->type);
            
            // Khusus barang temuan, hanya tampilkan yang sudah diverifikasi satpam
            if ($request->type == 'found') {
                $query->where('is_verified', true);
            }
        } else {
            // Jika menampilkan semua ("All"), tetap sembunyikan barang temuan yang belum verify
            $query->where(function($q) {
                $q->where('type', 'lost')
                ->orWhere(function($sub) {
                    $sub->where('type', 'found')->where('is_verified', true);
                });
            });
        }

        // AMBIL INPUT PER_PAGE (Default 12 jika tidak dipilih)
        $perPage = $request->input('per_page', 12);

        // Pastikan user tidak menginput angka aneh (validasi manual sederhana)
        if (!in_array($perPage, [12, 24, 48, 96])) {
            $perPage = 12;
        }

        // Gunakan variabel $perPage disini
        $items = $query->latest()->paginate($perPage)->withQueryString();

        return view('items.index', compact('items', 'categories'));
    }

    // Form Lapor (Hilang/Temuan)
    public function create()
    {
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    // Proses Simpan Laporan
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:lost,found',
            'date' => 'required|date',
            'location' => 'required|string',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048', // Max 2MB
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('items', 'public');
        }

        $user = auth()->user();
        $is_verified = false;
        $message = '';

        // Logika Verifikasi & Pesan
        if ($user->role === 'admin' || $user->role === 'security') {
            // Jika Petugas (Admin/Satpam) yang input, otomatis verified
            $is_verified = true;
            $message = 'Laporan berhasil dibuat dan telah terverifikasi otomatis.';
        } else {
            // Jika User Biasa (Mahasiswa) yang input
            $is_verified = false;

            if ($request->type === 'found') {
                // Pesan khusus barang temuan: Instruksi ke Pos Satpam
                $message = 'Laporan temuan berhasil dikirim. Mohon SEGERA bawa barang ke Pos Satpam untuk verifikasi fisik agar laporan tampil.';
            } else {
                // Pesan barang hilang: Menunggu Approval Admin
                $message = 'Laporan kehilangan berhasil dikirim. Menunggu persetujuan Admin untuk ditampilkan.';
            }
        }
        
        Item::create([
            'user_id' => $user->id,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'type' => $request->type,
            'description' => $request->description,
            'date' => $request->date,
            'location' => $request->location,
            'image_path' => $path,
            'is_verified' => $is_verified, 
            'status' => 'open',
        ]);

        return redirect()->route('items.index')->with('success', $message);
    }
    
    // Lihat Detail Barang
    public function show(Item $item)
    {
        return view('items.show', compact('item'));
    }
}
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

        // Query Dasar
        $query = Item::with('category', 'user')
            ->where('status', 'open'); // Hanya tampilkan barang yang masih aktif

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
        $perPage = $request->input('per_page', 10);

        // Pastikan user tidak menginput angka aneh (validasi manual sederhana)
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
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
            // Simpan gambar di folder 'items'
            $path = $request->file('image')->store('items', 'public');
        }

        Item::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'type' => $request->type,
            'description' => $request->description,
            'date' => $request->date,
            'location' => $request->location,
            'image_path' => $path,
            // Jika lapor "Hilang", otomatis verified. 
            // Jika "Temuan", butuh verifikasi satpam (false).
            'is_verified' => $request->type === 'lost' ? true : false,
        ]);

        return redirect()->route('items.index')->with('success', 'Laporan berhasil dibuat!');
    }
    
    // Lihat Detail Barang
    public function show(Item $item)
    {
        return view('items.show', compact('item'));
    }
}
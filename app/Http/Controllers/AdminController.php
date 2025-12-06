<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Claim;
use App\Models\User;      
use App\Models\Category; 
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
   // =========================================================
    // HALAMAN 1: DASHBOARD LAPORAN (Item Management)
    // =========================================================
    public function dashboard(Request $request)
    {
        $user = Auth::user();

        // JIKA SECURITY: Redirect ke tampilan khusus security (yang lama)
        if ($user->role === 'security') {
            $unverifiedFoundItems = Item::where('type', 'found')->where('is_verified', false)->latest()->get();
            $pendingClaims = Claim::where('status', 'pending')->with(['item', 'user'])->latest()->get();
            $approvedClaims = Claim::where('status', 'verified')->with(['item', 'user'])->latest()->get();
            return view('admin.dashboard_security', compact('unverifiedFoundItems', 'pendingClaims', 'approvedClaims'));
        }

        // JIKA ADMIN: Tampilkan Dashboard Laporan Lengkap
        if ($user->role === 'admin') {
            // 1. STATISTIK RINGKAS
            $stats = [
                'total' => Item::count(),
                'open' => Item::where('status', 'open')->count(),
                'claimed' => Item::where('status', 'claimed')->count(),
                'returned' => Item::where('status', 'returned')->count(),
                'cancelled' => Item::where('status', 'cancelled')->count(),
                'unverified' => Item::where('is_verified', false)->count(), // Aksi Cepat
            ];

            // 2. QUERY DATA LAPORAN
            $query = Item::with(['user', 'category']);

            // Aksi Cepat: Filter Unverified
            if ($request->get('filter') === 'unverified') {
                $query->where('is_verified', false);
            }

            // Filter Search
            if ($request->filled('search')) {
                $query->where(function($q) use ($request) {
                    $q->where('title', 'like', '%' . $request->search . '%')
                      ->orWhere('description', 'like', '%' . $request->search . '%');
                });
            }
            if ($request->filled('status')) $query->where('status', $request->status);
            if ($request->filled('type')) $query->where('type', $request->type);

            $items = $query->latest()->paginate($request->input('per_page', 10))->withQueryString();

            return view('admin.dashboard_admin', compact('stats', 'items'));
        }
        
        abort(403);
    }

    // =========================================================
    // HALAMAN 2: DASHBOARD KLAIM (Claim Management)
    // =========================================================
    public function claimsDashboard(Request $request)
    {
        // 1. STATISTIK KLAIM
        $stats = [
            'total' => Claim::count(),
            'pending' => Claim::where('status', 'pending')->count(),
            'verified' => Claim::where('status', 'verified')->count(),
            'rejected' => Claim::where('status', 'rejected')->count(),
            'completed' => Claim::where('status', 'completed')->count(),
        ];

        // 2. QUERY DATA KLAIM
        $query = Claim::with(['item', 'user']);

        // Aksi Cepat: Filter Pending & Ready for Pickup
        if ($request->get('filter') === 'pending') {
            $query->where('status', 'pending');
        }
        if ($request->get('filter') === 'ready') {
            $query->where('status', 'verified');
        }

        // Search (Nama Barang atau Nama User)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('item', function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            })->orWhereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }
        if ($request->filled('status')) $query->where('status', $request->status);

        $claims = $query->latest()->paginate($request->input('per_page', 10))->withQueryString();

        return view('admin.claims_dashboard', compact('stats', 'claims'));
    }

    // =========================================================
    // LOGIKA PROSES (VERIFIKASI & UPDATE)
    // =========================================================
    
    public function verifyItem(Item $item)
    {
        $item->update(['is_verified' => true]);
        return back()->with('success', 'Fisik barang berhasil diverifikasi.');
    }

    public function updateItemStatus(Request $request, Item $item)
    {
        $request->validate(['status' => 'required|in:open,cancelled,claimed,returned']);
        $item->update(['status' => $request->status]);
        return back()->with('success', 'Status laporan diperbarui.');
    }

    public function updateStatus(Request $request, Claim $claim)
    {
        $request->validate(['status' => 'required|in:verified,rejected,completed']);

        $claim->update([
            'status' => $request->status,
            'verified_by' => Auth::id(),
            'verification_notes' => $request->notes,
        ]);

        if ($request->status == 'verified') {
            $claim->item->update(['status' => 'claimed']);
        } elseif ($request->status == 'rejected') {
            $claim->item->update(['status' => 'open']);
        } elseif ($request->status == 'completed') {
             $claim->item->update(['status' => 'returned']);
        }

        return back()->with('success', 'Status klaim diperbarui.');
    }

    // =========================================================
    // MANAJEMEN USERS (CRUD)
    // =========================================================
    public function usersIndex() 
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function usersStore(Request $request) 
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role' => ['required', 'in:admin,security,mahasiswa'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        return back()->with('success', 'User berhasil ditambahkan.');
    }

    public function usersUpdate(Request $request, User $user) 
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role' => ['required', 'in:admin,security,mahasiswa'],
        ]);

        $data = $request->only('name', 'email', 'role');
        
        // Update password hanya jika diisi
        if($request->filled('password')) {
            $request->validate(['password' => ['confirmed', \Illuminate\Validation\Rules\Password::defaults()]]);
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $user->update($data);
        return back()->with('success', 'Data user diperbarui.');
    }

    public function usersDestroy(User $user) 
    {
        if($user->id === Auth::id()) return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        
        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }

    // =========================================================
    // MANAJEMEN KATEGORI (CRUD)
    // =========================================================
    public function categoriesIndex() 
    {
        $categories = Category::withCount('items')->latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function categoriesStore(Request $request) 
    {
        $request->validate(['name' => 'required|string|max:50|unique:categories']);
        Category::create($request->all());
        return back()->with('success', 'Kategori ditambahkan.');
    }

    public function categoriesUpdate(Request $request, Category $category) 
    {
        $request->validate(['name' => 'required|string|max:50|unique:categories,name,'.$category->id]);
        $category->update($request->all());
        return back()->with('success', 'Kategori diperbarui.');
    }

    public function categoriesDestroy(Category $category) 
    {
        if($category->items_count > 0) {
            return back()->with('error', 'Kategori ini sedang digunakan oleh barang, tidak bisa dihapus.');
        }
        $category->delete();
        return back()->with('success', 'Kategori dihapus.');
    }
}
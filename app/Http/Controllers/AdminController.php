<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Claim;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminController extends Controller
{
    // =========================================================
    // DASHBOARD UTAMA (Admin & Security)
    // =========================================================
    public function dashboard(Request $request)
    {
        $user = Auth::user();

        // ----------------------------------------------------
        // 1. LOGIKA UNTUK SECURITY
        // ----------------------------------------------------
        if ($user->role === 'security') {
            $unverifiedFoundItems = Item::where('type', 'found')->where('is_verified', false)->latest()->get();
            $pendingClaims = Claim::where('status', 'pending')->with(['item', 'user'])->latest()->get();
            $approvedClaims = Claim::where('status', 'verified')->with(['item', 'user'])->latest()->get();
            
            // Query untuk Tab Database Laporan (Lengkap)
            $query = Item::with(['user', 'category']);
            if ($request->filled('search')) {
                $query->where(function($q) use ($request) {
                    $q->where('title', 'like', '%' . $request->search . '%')
                      ->orWhere('description', 'like', '%' . $request->search . '%');
                });
            }
            if ($request->filled('status')) $query->where('status', $request->status);
            if ($request->filled('type')) $query->where('type', $request->type);
            
            // Variabel $allReports dikirim ke view security
            $allReports = $query->latest()->paginate($request->input('per_page', 10))->withQueryString();

            return view('admin.dashboard_security', compact(
                'unverifiedFoundItems', 
                'pendingClaims', 
                'approvedClaims',
                'allReports' 
            ));
        }

        // ----------------------------------------------------
        // 2. LOGIKA UNTUK ADMIN
        // ----------------------------------------------------
        if ($user->role === 'admin') {
            // A. DATA GRAFIK
            $months = collect([]);
            $lostStats = collect([]);
            $returnedStats = collect([]);
            
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $months->push($date->format('M Y'));
                $lostStats->push(Item::where('type', 'lost')->whereMonth('created_at', $date->month)->count());
                $returnedStats->push(Item::whereIn('status', ['returned', 'claimed'])->whereMonth('updated_at', $date->month)->count());
            }

            // B. STATISTIK KOTAK
            $stats = [
                'total' => Item::count(),
                'open' => Item::where('status', 'open')->count(),
                'claimed' => Item::where('status', 'claimed')->count(),
                'returned' => Item::where('status', 'returned')->count(),
                'cancelled' => Item::where('status', 'cancelled')->count(),
                'unverified' => Item::where('is_verified', false)->count(),
            ];

            // C. QUERY DATA LAPORAN (Main Table)
            $query = Item::with(['user', 'category']);

            // Filter Aksi Cepat
            if ($request->get('filter') === 'unverified') {
                $query->where('is_verified', false);
            }

            // Filter Pencarian & Status
            if ($request->filled('search')) {
                $query->where(function($q) use ($request) {
                    $q->where('title', 'like', '%' . $request->search . '%')
                      ->orWhere('description', 'like', '%' . $request->search . '%');
                });
            }
            if ($request->filled('status')) $query->where('status', $request->status);
            if ($request->filled('type')) $query->where('type', $request->type);

            // Variabel $allReports dikirim ke view admin (PERBAIKAN DISINI)
            $allReports = $query->latest()->paginate($request->input('per_page', 10))->withQueryString();

            return view('admin.dashboard_admin', compact(
                'stats', 
                'allReports', // <--- Pastikan nama variabel ini sama dengan di View
                'months', 
                'lostStats', 
                'returnedStats'
            ));
        }
        
        abort(403);
    }

    // =========================================================
    // HALAMAN 2: DASHBOARD KLAIM (Admin Only)
    // =========================================================
    public function claimsDashboard(Request $request)
    {
        $stats = [
            'total' => Claim::count(),
            'pending' => Claim::where('status', 'pending')->count(),
            'verified' => Claim::where('status', 'verified')->count(),
            'rejected' => Claim::where('status', 'rejected')->count(),
            'completed' => Claim::where('status', 'completed')->count(),
        ];

        $query = Claim::with(['item', 'user']);

        if ($request->get('filter') === 'pending') $query->where('status', 'pending');
        if ($request->get('filter') === 'ready') $query->where('status', 'verified');

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
    // LOGIKA PROSES
    // =========================================================
    public function verifyItem(Item $item)
    {
        $item->update(['is_verified' => true]);
        return back()->with('success', 'Laporan berhasil diverifikasi.');
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

    // CRUD User & Category
    public function usersIndex() { $users = User::latest()->paginate(10); return view('admin.users.index', compact('users')); }
    public function usersStore(Request $request) { 
        $request->validate(['name'=>'required','email'=>'required|email|unique:users','role'=>'required','password'=>'required|confirmed']);
        User::create(['name'=>$request->name,'email'=>$request->email,'role'=>$request->role,'password'=>Hash::make($request->password)]);
        return back()->with('success','User created.');
    }
    public function usersUpdate(Request $request, User $user) {
        $request->validate(['name'=>'required','email'=>'required|email|unique:users,email,'.$user->id,'role'=>'required']);
        $data=$request->only('name','email','role'); if($request->filled('password')){$data['password']=Hash::make($request->password);} $user->update($data);
        return back()->with('success','User updated.');
    }
    public function usersDestroy(User $user) { if($user->id===Auth::id())return back()->with('error','No self-delete.'); $user->delete(); return back()->with('success','User deleted.'); }
    
    public function categoriesIndex() { $categories = Category::withCount('items')->latest()->paginate(10); return view('admin.categories.index', compact('categories')); }
    public function categoriesStore(Request $request) { Category::create($request->validate(['name'=>'required|unique:categories'])); return back()->with('success','Category created.'); }
    public function categoriesUpdate(Request $request, Category $category) { $category->update($request->validate(['name'=>'required|unique:categories,name,'.$category->id])); return back()->with('success','Category updated.'); }
    public function categoriesDestroy(Category $category) { if($category->items_count>0)return back()->with('error','Category in use.'); $category->delete(); return back()->with('success','Deleted.'); }
}
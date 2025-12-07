<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="min-h-screen bg-slate-50 font-sans" 
         x-data="{ 
            search: '{{ request('search') }}',
            isLoading: false,
            submitSearch() {
                this.isLoading = true;
                clearTimeout(this.typingTimer);
                this.typingTimer = setTimeout(() => {
                    this.$refs.filterForm.submit();
                }, 600);
            }
         }">
        
        <div class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div>
                        <h1 class="text-2xl font-black text-slate-800 tracking-tight">Manajemen Laporan</h1>
                        <p class="text-xs text-slate-500">Moderasi laporan masuk dan update status.</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-200 transition border border-slate-200">Manajemen User</a>
                        <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-200 transition border border-slate-200">Kategori</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 animate-fade-in">
                <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Laporan</p>
                    <p class="text-3xl font-black text-slate-800 mt-1">{{ $stats['total'] }}</p>
                </div>
                
                <a href="{{ route('admin.dashboard', ['filter' => 'unverified']) }}" class="relative overflow-hidden bg-orange-500 p-4 rounded-2xl border border-orange-600 shadow-lg shadow-orange-200 group hover:scale-105 transition cursor-pointer text-white flex flex-col justify-between">
                    <div class="absolute right-0 top-0 p-3 opacity-10 transform translate-x-2 -translate-y-2">
                        <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-orange-100 uppercase tracking-wider">Butuh Approval</p>
                        <p class="text-3xl font-black mt-1">{{ $stats['unverified'] }}</p>
                    </div>
                    <div class="flex items-center text-[10px] font-bold mt-2 bg-orange-600/30 w-fit px-2 py-1 rounded-lg backdrop-blur-sm">
                        Klik untuk Filter <svg class="w-3 h-3 ml-1 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </div>
                </a>

                <div class="bg-white p-4 rounded-2xl border border-sky-100 shadow-sm hover:border-sky-300 transition">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Status: Open</p>
                    <p class="text-2xl font-black text-sky-600 mt-1">{{ $stats['open'] }}</p>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-yellow-100 shadow-sm hover:border-yellow-300 transition">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Status: Claimed</p>
                    <p class="text-2xl font-black text-yellow-600 mt-1">{{ $stats['claimed'] }}</p>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-emerald-100 shadow-sm hover:border-emerald-300 transition">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Status: Returned</p>
                    <p class="text-2xl font-black text-emerald-600 mt-1">{{ $stats['returned'] }}</p>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm hover:border-slate-400 transition">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Status: Cancelled</p>
                    <p class="text-2xl font-black text-slate-500 mt-1">{{ $stats['cancelled'] }}</p>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden animate-fade-in" style="animation-delay: 0.1s;">
                <div class="p-6 border-b border-slate-100 bg-slate-50/30">
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-4">
                        <h3 class="font-bold text-lg text-slate-800 flex items-center gap-2">
                            <span class="w-1.5 h-6 bg-slate-800 rounded-full"></span>
                            Database Laporan
                        </h3>
                        @if(request('filter') == 'unverified')
                            <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-rose-500 bg-rose-50 px-3 py-1.5 rounded-lg hover:bg-rose-100 transition flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                Hapus Filter
                            </a>
                        @endif
                    </div>

                    {{-- FORM FILTER & SEARCH --}}
                    <form x-ref="filterForm" action="{{ route('admin.dashboard') }}" method="GET" class="grid grid-cols-2 md:grid-cols-5 gap-3">
                        <div class="col-span-2 md:col-span-1 flex items-center gap-2">
                            <span class="text-xs font-bold text-slate-500">Show</span>
                            <select name="per_page" onchange="this.form.submit()" class="text-xs border-slate-200 rounded-xl focus:ring-slate-500 w-full cursor-pointer bg-white">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            </select>
                        </div>
                        <select name="status" onchange="this.form.submit()" class="text-xs border-slate-200 rounded-xl focus:ring-slate-500 cursor-pointer bg-white">
                            <option value="">Semua Status</option>
                            <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="claimed" {{ request('status') == 'claimed' ? 'selected' : '' }}>Claimed</option>
                            <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        <select name="type" onchange="this.form.submit()" class="text-xs border-slate-200 rounded-xl focus:ring-slate-500 cursor-pointer bg-white">
                            <option value="">Semua Tipe</option>
                            <option value="lost" {{ request('type') == 'lost' ? 'selected' : '' }}>Hilang</option>
                            <option value="found" {{ request('type') == 'found' ? 'selected' : '' }}>Temuan</option>
                        </select>
                        <div class="col-span-2">
                            <input type="text" name="search" x-model="search" @input="submitSearch()" placeholder="Cari Judul / ID / Deskripsi..." class="w-full text-xs border-slate-200 rounded-xl focus:ring-slate-500 bg-white">
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold tracking-wider">
                            <tr>
                                <th class="px-6 py-4 w-12 text-center">No</th>
                                <th class="px-6 py-4">Barang</th>
                                <th class="px-6 py-4">Kategori</th>
                                <th class="px-6 py-4">Pelapor</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-center">Approval</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($allReports as $index => $item)
                            <tr class="hover:bg-slate-50/80 transition group">
                                <td class="px-6 py-4 text-center font-bold text-slate-400">{{ $allReports->firstItem() + $index }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $item->image_path ? asset('storage/'.$item->image_path) : 'https://via.placeholder.com/50' }}" class="w-12 h-12 rounded-xl object-cover bg-slate-100 border border-slate-200 shadow-sm">
                                        <div>
                                            <p class="font-bold text-slate-800 line-clamp-1 w-40 group-hover:text-sky-600 transition">{{ $item->title }}</p>
                                            <span class="text-[10px] font-bold uppercase tracking-wide {{ $item->type == 'lost' ? 'text-rose-500' : 'text-emerald-500' }}">{{ $item->type == 'lost' ? 'Hilang' : 'Temuan' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs font-bold">{{ $item->category->name }}</td>
                                <td class="px-6 py-4 text-xs">{{ $item->user->name }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide border
                                        @if($item->status == 'open') bg-sky-50 text-sky-700 border-sky-100
                                        @elseif($item->status == 'cancelled') bg-rose-50 text-rose-600 border-rose-200
                                        @elseif($item->status == 'claimed') bg-yellow-50 text-yellow-700 border-yellow-100
                                        @elseif($item->status == 'returned') bg-emerald-50 text-emerald-700 border-emerald-100
                                        @endif">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($item->status == 'cancelled')
                                        {{-- LOGIC 1: Jika Status Cancelled --}}
                                        <div class="inline-flex items-center text-rose-600 bg-rose-50 px-2 py-1 rounded-lg text-[10px] font-black border border-rose-200 shadow-sm opacity-80 cursor-not-allowed">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Cancelled
                                        </div>
                                    @elseif($item->is_verified)
                                        {{-- LOGIC 2: Jika Sudah Verified --}}
                                        <div class="inline-flex items-center text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg text-[10px] font-bold border border-emerald-100">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Approved
                                        </div>
                                    @else
                                        {{-- LOGIC 3: Jika Belum Verified (Tombol Approve Muncul) --}}
                                        <form action="{{ route('admin.items.verify', $item) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button onclick="confirmSubmit(event, 'Approve laporan ini agar tampil publik?')" class="text-[10px] bg-orange-500 text-white px-3 py-1.5 rounded-lg font-bold shadow-md shadow-orange-200 hover:bg-orange-600 hover:shadow-lg transition transform hover:-translate-y-0.5">
                                                Approve
                                            </button>
                                        </form>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div x-data="{ detailOpen: false, editOpen: false }">
                                        <div class="flex justify-end gap-1">
                                            <button @click="detailOpen = true" class="p-2 text-slate-400 hover:text-sky-600 bg-white border border-slate-200 rounded-lg hover:bg-sky-50 transition shadow-sm" title="Lihat Detail"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></button>
                                            <button @click="editOpen = true" class="p-2 text-slate-400 hover:text-orange-600 bg-white border border-slate-200 rounded-lg hover:bg-orange-50 transition shadow-sm" title="Ubah Status Manual"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>
                                        </div>

                                        <template x-teleport="body">
                                            <div x-show="detailOpen" class="fixed inset-0 z-[99] flex items-center justify-center bg-black/60 backdrop-blur-md p-4" 
                                                 style="display: none;"
                                                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                                                
                                                <div class="bg-white rounded-[2rem] w-full max-w-4xl overflow-hidden shadow-2xl relative flex flex-col md:flex-row transform transition-all" @click.away="detailOpen = false"
                                                     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                                     x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-90 translate-y-4">
                                                    
                                                    <button @click="detailOpen = false" class="absolute top-4 right-4 z-50 bg-black/30 hover:bg-black/50 text-white p-2 rounded-full backdrop-blur-md transition">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    </button>

                                                    <div class="w-full md:w-1/2 bg-slate-900 relative min-h-[300px]">
                                                        @if($item->image_path) 
                                                            <img src="{{ asset('storage/'.$item->image_path) }}" class="w-full h-full object-cover opacity-90"> 
                                                        @else
                                                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-500 bg-slate-100">
                                                                <svg class="w-16 h-16 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                                <span class="text-xs mt-2 font-bold uppercase tracking-widest opacity-50">No Image</span>
                                                            </div>
                                                        @endif
                                                        <div class="absolute top-6 left-6">
                                                            <span class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg backdrop-blur-md text-white border border-white/20
                                                                {{ $item->type == 'lost' ? 'bg-rose-500' : 'bg-emerald-500' }}">
                                                                {{ $item->type == 'lost' ? 'Laporan Hilang' : 'Laporan Temuan' }}
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <div class="w-full md:w-1/2 p-8 md:p-10 flex flex-col">
                                                        <div>
                                                            <div class="flex items-center justify-between mb-2">
                                                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ $item->category->name }}</span>
                                                                <span class="text-xs font-bold text-slate-400">{{ $item->created_at->format('d M Y, H:i') }}</span>
                                                            </div>
                                                            <h2 class="text-3xl font-black text-slate-800 leading-tight mb-6">{{ $item->title }}</h2>
                                                            
                                                            <div class="space-y-4 mb-8">
                                                                <div>
                                                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Deskripsi</label>
                                                                    <p class="text-sm text-slate-600 bg-slate-50 p-4 rounded-xl border border-slate-100 leading-relaxed">{{ $item->description }}</p>
                                                                </div>
                                                                <div class="grid grid-cols-2 gap-4">
                                                                    <div>
                                                                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Lokasi</label>
                                                                        <p class="text-sm font-bold text-slate-800 flex items-center">
                                                                            <svg class="w-4 h-4 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                                                            {{ $item->location }}
                                                                        </p>
                                                                    </div>
                                                                    <div>
                                                                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Pelapor</label>
                                                                        <p class="text-sm font-bold text-slate-800">{{ $item->user->name }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="mt-auto pt-6 border-t border-slate-100 flex items-center justify-between">
                                                            <div class="flex items-center gap-2">
                                                                <span class="text-xs font-bold text-slate-400">Status:</span>
                                                                <span class="px-2 py-1 rounded text-xs font-bold uppercase bg-slate-100 text-slate-600">{{ $item->status }}</span>
                                                            </div>
                                                            
                                                            @if(!$item->is_verified)
                                                                <form action="{{ route('admin.items.verify', $item) }}" method="POST">
                                                                    @csrf @method('PATCH')
                                                                    <button onclick="confirmSubmit(event, 'Approve laporan ini?')" class="bg-orange-500 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-orange-200 hover:bg-orange-600 hover:-translate-y-0.5 transition">
                                                                        Approve Sekarang
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <span class="text-emerald-500 text-sm font-bold flex items-center">
                                                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                                    Sudah Diapprove
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>

                                        <template x-teleport="body">
                                            <div x-show="editOpen" class="fixed inset-0 z-[99] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4" 
                                                 style="display: none;"
                                                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                                                
                                                <div class="bg-white rounded-[2rem] w-full max-w-sm p-8 shadow-2xl relative transform transition-all" @click.away="editOpen = false"
                                                     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                                     x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-90 translate-y-4">
                                                    
                                                    <div class="flex justify-between items-center mb-6">
                                                        <h3 class="text-xl font-black text-slate-800">Update Status Laporan</h3>
                                                        <button @click="editOpen = false" class="text-slate-400 hover:text-slate-600 bg-slate-100 p-2 rounded-full transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                                    </div>

                                                    <form action="{{ route('admin.items.update-status', $item) }}" method="POST" class="space-y-3">
                                                        @csrf @method('PATCH')
                                                        
                                                        <label class="flex items-center p-3 border rounded-xl cursor-pointer hover:bg-sky-50 transition {{ $item->status == 'open' ? 'border-sky-500 bg-sky-50 ring-1 ring-sky-500' : 'border-slate-200' }}">
                                                            <input type="radio" name="status" value="open" {{ $item->status == 'open' ? 'checked' : '' }} class="text-sky-500 focus:ring-sky-500">
                                                            <div class="ml-3"><span class="block text-sm font-bold text-slate-800">Open</span><span class="text-[10px] text-slate-500">Aktif & Terlihat Publik</span></div>
                                                        </label>
                                                        
                                                        <label class="flex items-center p-3 border rounded-xl cursor-pointer hover:bg-rose-50 transition {{ $item->status == 'cancelled' ? 'border-rose-500 bg-rose-50 ring-1 ring-rose-500' : 'border-slate-200' }}">
                                                            <input type="radio" name="status" value="cancelled" {{ $item->status == 'cancelled' ? 'checked' : '' }} class="text-rose-500 focus:ring-rose-500">
                                                            <div class="ml-3"><span class="block text-sm font-bold text-slate-800">Cancelled</span><span class="text-[10px] text-slate-500">Batalkan / Spam</span></div>
                                                        </label>
                                                        
                                                        @if($item->type == 'found')
                                                        <label class="flex items-center p-3 border rounded-xl cursor-pointer hover:bg-yellow-50 transition {{ $item->status == 'claimed' ? 'border-yellow-500 bg-yellow-50 ring-1 ring-yellow-500' : 'border-slate-200' }}">
                                                            <input type="radio" name="status" value="claimed" {{ $item->status == 'claimed' ? 'checked' : '' }} class="text-yellow-500 focus:ring-yellow-500">
                                                            <div class="ml-3"><span class="block text-sm font-bold text-slate-800">Claimed</span><span class="text-[10px] text-slate-500">Proses Klaim Manual</span></div>
                                                        </label>
                                                        
                                                        <label class="flex items-center p-3 border rounded-xl cursor-pointer hover:bg-emerald-50 transition {{ $item->status == 'returned' ? 'border-emerald-500 bg-emerald-50 ring-1 ring-emerald-500' : 'border-slate-200' }}">
                                                            <input type="radio" name="status" value="returned" {{ $item->status == 'returned' ? 'checked' : '' }} class="text-emerald-500 focus:ring-emerald-500">
                                                            <div class="ml-3"><span class="block text-sm font-bold text-slate-800">Returned</span><span class="text-[10px] text-slate-500">Selesai / Dikembalikan</span></div>
                                                        </label>
                                                        @endif
                                                        
                                                        <div class="pt-4 flex gap-3">
                                                            <button type="button" @click="editOpen = false" class="flex-1 py-3 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition">Batal</button>
                                                            <button type="submit" class="flex-1 py-3 bg-slate-900 text-white font-bold rounded-xl hover:bg-slate-800 transition shadow-lg">Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center py-12 text-slate-400 italic">Tidak ada data laporan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">{{ $allReports->links() }}</div>
            </div>
        </div>
    </div>
    <style>.animate-fade-in { animation: fadeIn 0.5s ease-out; } @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }</style>
</x-app-layout>
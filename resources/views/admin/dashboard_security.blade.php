<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="min-h-screen bg-slate-50 font-sans" 
         x-data="{ 
            activeTab: '{{ request('tab', 'items_found') }}',
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
        
        <div class="bg-white border-b border-orange-200 shadow-sm sticky top-0 z-30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div>
                        <h1 class="text-2xl font-black text-slate-800">Security Command Center</h1>
                        <p class="text-xs text-slate-500">Halo, <span class="font-bold text-sky-600">{{ Auth::user()->name }}</span>. Selamat bertugas!</p>
                    </div>
                    
                    <a href="{{ route('items.create') }}" class="group flex items-center px-5 py-2.5 bg-slate-900 text-white text-sm font-bold rounded-xl hover:bg-slate-800 transition shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2 text-sky-400 group-hover:text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Input Barang Temuan
                    </a>
                </div>

                <div class="mt-6 flex flex-wrap gap-2">
                    <button @click="activeTab = 'items_found'" :class="activeTab === 'items_found' ? 'bg-sky-500 text-white shadow-lg shadow-sky-200' : 'bg-white text-slate-500 border border-slate-200 hover:bg-slate-50'" class="px-5 py-2.5 rounded-xl font-bold text-xs transition whitespace-nowrap flex items-center">
                        Verifikasi Fisik
                        @if($unverifiedFoundItems->count() > 0) <span class="ml-2 bg-white text-sky-600 px-1.5 py-0.5 rounded text-[10px]">{{ $unverifiedFoundItems->count() }}</span> @endif
                    </button>
                    <button @click="activeTab = 'claims'" :class="activeTab === 'claims' ? 'bg-yellow-400 text-white shadow-lg shadow-yellow-200' : 'bg-white text-slate-500 border border-slate-200 hover:bg-slate-50'" class="px-5 py-2.5 rounded-xl font-bold text-xs transition whitespace-nowrap flex items-center">
                        Klaim Masuk
                        @if($pendingClaims->count() > 0) <span class="ml-2 bg-white text-yellow-500 px-1.5 py-0.5 rounded text-[10px]">{{ $pendingClaims->count() }}</span> @endif
                    </button>
                    <button @click="activeTab = 'handover'" :class="activeTab === 'handover' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-200' : 'bg-white text-slate-500 border border-slate-200 hover:bg-slate-50'" class="px-5 py-2.5 rounded-xl font-bold text-xs transition whitespace-nowrap flex items-center">
                        Serah Terima
                        @if($approvedClaims->count() > 0) <span class="ml-2 bg-white text-emerald-600 px-1.5 py-0.5 rounded text-[10px]">{{ $approvedClaims->count() }}</span> @endif
                    </button>
                    <button @click="activeTab = 'database'" :class="activeTab === 'database' ? 'bg-slate-800 text-white shadow-lg' : 'bg-white text-slate-500 border border-slate-200 hover:bg-slate-50'" class="px-5 py-2.5 rounded-xl font-bold text-xs transition whitespace-nowrap flex items-center">
                        Database Laporan
                    </button>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
            
            <div x-show="activeTab === 'items_found'" class="animate-fade-in">
                @if($unverifiedFoundItems->isEmpty())
                    <div class="flex flex-col items-center justify-center py-20 bg-white rounded-[2rem] border-2 border-dashed border-slate-200 text-center"><div class="bg-slate-50 p-4 rounded-full mb-3"><svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div><p class="text-slate-400 font-bold">Semua barang temuan aman.</p></div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($unverifiedFoundItems as $item)
                        <div x-data="{ modalOpen: false }" class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition group">
                            <div @click="modalOpen = true" class="cursor-pointer">
                                <div class="relative h-40 bg-slate-100 rounded-2xl overflow-hidden mb-4">
                                    @if($item->image_path) <img src="{{ asset('storage/'.$item->image_path) }}" class="w-full h-full object-cover transition transform group-hover:scale-105"> @endif
                                    <div class="absolute top-2 right-2 bg-orange-500 text-white text-[10px] font-bold px-2 py-1 rounded-lg shadow-sm">Cek Fisik</div>
                                </div>
                                <h4 class="font-bold text-slate-800 line-clamp-1 text-lg">{{ $item->title }}</h4>
                                <p class="text-xs text-slate-500 mb-3 flex items-center"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>{{ Str::limit($item->location, 30) }}</p>
                            </div>
                            
                            <button onclick="verifyAction('{{ $item->id }}')" class="w-full py-3 bg-sky-500 text-white font-bold rounded-xl text-xs hover:bg-sky-600 shadow-lg shadow-sky-200 transition transform hover:-translate-y-0.5">
                                Review & Verifikasi
                            </button>

                            <form id="verify-form-{{ $item->id }}" action="{{ route('admin.items.verify', $item) }}" method="POST" style="display: none;">
                                @csrf @method('PATCH')
                                <input type="hidden" name="action" id="action-input-{{ $item->id }}">
                            </form>

                            <template x-teleport="body">
                                <div x-show="modalOpen" class="fixed inset-0 z-[99] flex items-center justify-center bg-black/60 backdrop-blur-md p-4" 
                                     style="display: none;"
                                     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                     x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                                    
                                    <div class="bg-white rounded-[2rem] w-full max-w-4xl overflow-hidden shadow-2xl relative flex flex-col md:flex-row transform transition-all" @click.away="modalOpen = false"
                                         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-90 translate-y-4">
                                        
                                        <button @click="modalOpen = false" class="absolute top-4 right-4 z-50 bg-black/30 hover:bg-black/50 text-white p-2 rounded-full backdrop-blur-md transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>

                                        <div class="w-full md:w-1/2 bg-slate-900 relative min-h-[300px]">
                                            @if($item->image_path) <img src="{{ asset('storage/'.$item->image_path) }}" class="w-full h-full object-cover opacity-90"> @else <div class="w-full h-full flex flex-col items-center justify-center text-slate-500 bg-slate-100">No Image</div> @endif
                                            <div class="absolute top-6 left-6">
                                                <span class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg backdrop-blur-md text-white border border-white/20 bg-orange-500">
                                                    Verifikasi Fisik
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
                                                    <div><label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Deskripsi</label><p class="text-sm text-slate-700 bg-slate-50 p-4 rounded-xl border border-slate-100 leading-relaxed">{{ $item->description }}</p></div>
                                                    <div class="grid grid-cols-2 gap-4">
                                                        <div><label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Lokasi</label><p class="text-sm font-bold text-slate-800">{{ $item->location }}</p></div>
                                                        <div><label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Pelapor</label><p class="text-sm font-bold text-slate-800">{{ $item->user->name }}</p></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-auto pt-6 border-t border-slate-100">
                                                <button onclick="verifyAction('{{ $item->id }}')" class="w-full py-3.5 bg-sky-600 text-white font-bold rounded-xl text-sm shadow-xl hover:bg-sky-700 hover:-translate-y-0.5 transition flex justify-center items-center">
                                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Konfirmasi Penerimaan Fisik
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div x-show="activeTab === 'claims'" style="display: none;" class="animate-fade-in">
                @if($pendingClaims->isEmpty())
                    <div class="flex flex-col items-center justify-center py-20 bg-white rounded-[2rem] border-2 border-dashed border-slate-200 text-center"><div class="bg-yellow-50 p-4 rounded-full mb-3"><svg class="w-10 h-10 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div><p class="text-slate-400 font-bold">Tidak ada klaim pending.</p></div>
                @else
                    <div class="space-y-4">
                        @foreach($pendingClaims as $claim)
                        <div x-data="{ modalOpen: false }" class="bg-white p-6 rounded-3xl border border-yellow-100 shadow-sm flex flex-col md:flex-row gap-6 hover:shadow-md transition">
                            <div class="flex-1 flex gap-4 cursor-pointer" @click="modalOpen = true">
                                <img src="{{ $claim->item->image_path ? asset('storage/'.$claim->item->image_path) : 'https://via.placeholder.com/100' }}" class="w-20 h-20 rounded-2xl object-cover bg-slate-100">
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">Klaim Barang</span>
                                    <h4 class="font-bold text-slate-800 text-lg leading-tight">{{ $claim->item->title }}</h4>
                                    <p class="text-xs text-slate-500 mt-1">Oleh: <span class="font-bold text-slate-700">{{ $claim->user->name }}</span></p>
                                    <div class="mt-2 text-xs font-bold text-sky-500 bg-sky-50 px-2 py-1 rounded-lg w-fit">Klik untuk detail bukti</div>
                                </div>
                            </div>
                            <div class="flex flex-col gap-2 justify-center w-full md:w-48">
                                <button @click="modalOpen = true" class="w-full py-2 bg-slate-900 text-white font-bold rounded-xl text-sm shadow-md hover:bg-slate-800">Proses Klaim</button>
                            </div>
                            
                            <template x-teleport="body">
                                <div x-show="modalOpen" class="fixed inset-0 z-[99] flex items-center justify-center bg-black/60 backdrop-blur-md p-4" style="display: none;" 
                                     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                                     x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                                    
                                    <div class="bg-white rounded-[2rem] w-full max-w-4xl overflow-hidden shadow-2xl relative flex flex-col md:flex-row transform transition-all" @click.away="modalOpen = false"
                                         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-90 translate-y-4">
                                        
                                        <button @click="modalOpen = false" class="absolute top-4 right-4 z-50 bg-black/30 hover:bg-black/50 text-white p-2 rounded-full backdrop-blur-md transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                        <div class="w-full md:w-5/12 bg-slate-900 relative min-h-[300px]">@if($claim->item->image_path) <img src="{{ asset('storage/'.$claim->item->image_path) }}" class="absolute inset-0 w-full h-full object-cover opacity-80"> @endif<div class="absolute bottom-6 left-6 text-white"><p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Barang</p><h3 class="text-xl font-black leading-tight">{{ $claim->item->title }}</h3></div></div>
                                        <div class="w-full md:w-7/12 p-8 md:p-10 flex flex-col">
                                            <div class="mb-6">
                                                <div class="bg-yellow-50 p-4 rounded-2xl border border-yellow-100 mb-4"><p class="text-[10px] font-bold text-yellow-600 uppercase tracking-wider mb-1">Bukti Deskripsi</p><p class="text-sm text-slate-700 italic leading-relaxed">"{{ $claim->proof_description }}"</p></div>
                                                @if($claim->proof_file)<div class="mt-2"><p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Foto Bukti</p><a href="{{ asset('storage/'.$claim->proof_file) }}" target="_blank" class="block relative group overflow-hidden rounded-xl border border-slate-200"><img src="{{ asset('storage/'.$claim->proof_file) }}" class="w-full h-32 object-cover"></a></div>@endif
                                            </div>
                                            <div class="mt-auto pt-6 border-t border-slate-100 grid grid-cols-2 gap-3">
                                                <form action="{{ route('admin.claims.update', $claim) }}" method="POST">@csrf @method('PATCH')<button name="status" value="rejected" onclick="confirmSubmit(event, 'Tolak klaim ini?')" class="w-full py-3 bg-white border border-rose-200 text-rose-600 font-bold rounded-xl text-sm hover:bg-rose-50 transition">Tolak</button></form>
                                                <form action="{{ route('admin.claims.update', $claim) }}" method="POST">@csrf @method('PATCH')<button name="status" value="verified" onclick="confirmSubmit(event, 'Bukti valid?')" class="w-full py-3 bg-emerald-500 text-white font-bold rounded-xl text-sm shadow-lg shadow-emerald-200 hover:bg-emerald-600 transition">Terima (Valid)</button></form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div x-show="activeTab === 'handover'" style="display: none;" class="animate-fade-in">
                @if($approvedClaims->isEmpty())
                    <div class="flex flex-col items-center justify-center py-20 bg-white rounded-[2rem] border-2 border-dashed border-slate-200 text-center"><div class="bg-emerald-50 p-4 rounded-full mb-3"><svg class="w-10 h-10 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div><p class="text-slate-400 font-bold">Belum ada barang siap diambil.</p></div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($approvedClaims as $claim)
                        <div class="bg-white p-6 rounded-3xl border border-emerald-100 shadow-sm relative overflow-hidden flex flex-col justify-between">
                            <div class="absolute top-0 right-0 bg-emerald-500 text-white text-[10px] font-bold px-3 py-1 rounded-bl-xl shadow-md">SIAP DIAMBIL</div>
                            <div class="flex items-center gap-4 mb-6 mt-2">
                                <img src="{{ $claim->item->image_path ? asset('storage/'.$claim->item->image_path) : 'https://via.placeholder.com/100' }}" class="w-20 h-20 rounded-2xl object-cover bg-slate-100 shadow-sm">
                                <div><h4 class="font-bold text-slate-800 text-lg">{{ $claim->item->title }}</h4><p class="text-sm text-slate-500 mt-1">Pemilik Sah: <span class="font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded">{{ $claim->user->name }}</span></p></div>
                            </div>
                            <form action="{{ route('admin.claims.update', $claim) }}" method="POST">@csrf @method('PATCH')<button name="status" value="completed" onclick="confirmSubmit(event, 'Barang sudah diserahkan?')" class="w-full py-3 bg-slate-900 text-white font-bold rounded-xl text-sm hover:bg-slate-800 shadow-lg transition flex justify-center items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Konfirmasi Serah Terima Selesai</button></form>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div x-show="activeTab === 'database'" style="display: none;" class="animate-fade-in">
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 bg-slate-50/30">
                        <form x-ref="filterForm" action="{{ route('admin.dashboard') }}" method="GET" class="grid grid-cols-2 md:grid-cols-5 gap-3">
                            <input type="hidden" name="tab" value="database">
                            <div class="col-span-2 md:col-span-1 flex items-center gap-2"><span class="text-xs font-bold text-slate-500">Show</span><select id="sec-perpage" class="sec-filter text-xs border-slate-200 rounded-xl focus:ring-slate-500 w-full cursor-pointer bg-white"><option value="10">10</option><option value="25">25</option><option value="50">50</option></select></div>
                            <select id="sec-status" class="sec-filter text-xs border-slate-200 rounded-xl focus:ring-slate-500 cursor-pointer bg-white"><option value="">Semua Status</option><option value="open">Open</option><option value="claimed">Claimed</option><option value="returned">Returned</option><option value="cancelled">Cancelled</option></select>
                            <select id="sec-type" class="sec-filter text-xs border-slate-200 rounded-xl focus:ring-slate-500 cursor-pointer bg-white"><option value="">Semua Tipe</option><option value="lost">Hilang</option><option value="found">Temuan</option></select>
                            <div class="col-span-2"><input type="text" id="sec-search" value="{{ request('search') }}" placeholder="Cari Judul / ID / Deskripsi..." class="w-full text-xs border-slate-200 rounded-xl focus:ring-slate-500 bg-white"></div>
                        </form>
                    </div>
                    <div id="security-table-container">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-slate-600">
                                <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold tracking-wider">
                                    <tr><th class="px-6 py-4 w-12 text-center">No</th><th class="px-6 py-4">Barang</th><th class="px-6 py-4">Kategori</th><th class="px-6 py-4">Pelapor</th><th class="px-6 py-4 text-center">Status</th><th class="px-6 py-4 text-center">Verifikasi</th><th class="px-6 py-4 text-right">Aksi</th></tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($allReports as $index => $item)
                                    <tr class="hover:bg-slate-50/80 transition group">
                                        <td class="px-6 py-4 text-center font-bold text-slate-400">{{ $allReports->firstItem() + $index }}</td>
                                        <td class="px-6 py-4"><div class="flex items-center gap-3"><img src="{{ $item->image_path ? asset('storage/'.$item->image_path) : 'https://via.placeholder.com/50' }}" class="w-12 h-12 rounded-xl object-cover bg-slate-100 border border-slate-200 shadow-sm"><div><p class="font-bold text-slate-800 line-clamp-1 w-40 group-hover:text-sky-600 transition">{{ $item->title }}</p><span class="text-[10px] font-bold uppercase {{ $item->type == 'lost' ? 'text-rose-500' : 'text-emerald-500' }}">{{ $item->type == 'lost' ? 'Hilang' : 'Temuan' }}</span></div></div></td>
                                        <td class="px-6 py-4 text-xs font-bold">{{ $item->category->name }}</td>
                                        <td class="px-6 py-4 text-xs">{{ $item->user->name }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide border @if($item->status == 'open') bg-sky-50 text-sky-700 border-sky-100 @elseif($item->status == 'cancelled') bg-rose-50 text-rose-600 border-rose-200 @elseif($item->status == 'claimed') bg-yellow-50 text-yellow-700 border-yellow-100 @elseif($item->status == 'returned') bg-emerald-50 text-emerald-700 border-emerald-100 @endif">{{ $item->status }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($item->status == 'cancelled')
                                                <div class="inline-flex items-center text-rose-600 bg-rose-50 px-2 py-1 rounded-lg text-[10px] font-bold border border-rose-200"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> Dibatalkan</div>
                                            @elseif($item->is_verified)
                                                <div class="inline-flex items-center text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg text-[10px] font-bold border border-emerald-100"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Verified</div>
                                            @else
                                                <span class="text-[10px] bg-orange-100 text-orange-600 px-2 py-1 rounded font-bold border border-orange-200">Pending</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div x-data="{ detailOpen: false }">
                                                <button @click="detailOpen = true" class="ml-auto flex items-center px-3 py-2 text-sky-600 bg-sky-50 hover:bg-sky-100 border border-sky-100 rounded-xl transition shadow-sm text-xs font-bold" title="Lihat Detail">Lihat</button>
                                                
                                                <template x-teleport="body">
                                                    <div x-show="detailOpen" class="fixed inset-0 z-[99] flex items-center justify-center bg-black/60 backdrop-blur-md p-4" 
                                                         style="display: none;" 
                                                         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                                                         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                                                        
                                                        <div class="bg-white rounded-[2rem] w-full max-w-4xl overflow-hidden shadow-2xl relative flex flex-col md:flex-row transform transition-all" 
                                                             @click.away="detailOpen = false"
                                                             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
                                                             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-90 translate-y-4">
                                                            
                                                            <button @click="detailOpen = false" class="absolute top-4 right-4 z-50 bg-black/30 hover:bg-black/50 text-white p-2 rounded-full backdrop-blur-md transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                                            
                                                            <div class="w-full md:w-1/2 bg-slate-900 relative min-h-[300px]">
                                                                @if($item->image_path) <img src="{{ asset('storage/'.$item->image_path) }}" class="w-full h-full object-cover opacity-90"> @endif
                                                                <div class="absolute top-6 left-6"><span class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg backdrop-blur-md text-white border border-white/20 {{ $item->type == 'lost' ? 'bg-rose-500' : 'bg-emerald-500' }}">{{ $item->type == 'lost' ? 'Laporan Hilang' : 'Laporan Temuan' }}</span></div>
                                                            </div>
                                                            
                                                            <div class="w-full md:w-1/2 p-8 flex flex-col">
                                                                <div>
                                                                    <div class="flex items-center justify-between mb-2"><span class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ $item->category->name }}</span><span class="text-xs font-bold text-slate-400">{{ $item->created_at->format('d M Y') }}</span></div>
                                                                    <h2 class="text-3xl font-black text-slate-800 leading-tight mb-6">{{ $item->title }}</h2>
                                                                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 text-sm text-slate-700 mb-6 leading-relaxed">{{ $item->description }}</div>
                                                                    <div class="grid grid-cols-2 gap-4 text-xs text-slate-500 mt-auto"><div><b>Pelapor:</b> {{ $item->user->name }}</div><div><b>Lokasi:</b> {{ $item->location }}</div><div><b>Status:</b> <span class="uppercase font-bold text-slate-700">{{ $item->status }}</span></div></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty <tr><td colspan="7" class="text-center py-10 text-slate-400 italic">Tidak ada data laporan.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="p-4 border-t border-slate-100 bg-slate-50/50">{{ $allReports->links() }}</div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('sec-search');
            const filters = document.querySelectorAll('.sec-filter');
            const container = document.getElementById('security-table-container');
            let timeout = null;

            function fetchResults() {
                const search = searchInput.value;
                const status = document.getElementById('sec-status').value;
                const type = document.getElementById('sec-type').value;
                const perPage = document.getElementById('sec-perpage').value;

                const url = new URL(window.location.href);
                url.searchParams.set('search', search);
                url.searchParams.set('status', status);
                url.searchParams.set('type', type);
                url.searchParams.set('per_page', perPage);
                url.searchParams.set('tab', 'database');
                url.searchParams.delete('page');

                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newContainer = doc.getElementById('security-table-container');
                    
                    if (newContainer && container) {
                        container.innerHTML = newContainer.innerHTML;
                        if (window.Alpine) window.Alpine.initTree(container);
                        window.history.pushState({}, '', url);
                    }
                });
            }

            if(searchInput) {
                searchInput.addEventListener('input', () => {
                    clearTimeout(timeout);
                    timeout = setTimeout(fetchResults, 500);
                });
            }

            if(filters) {
                filters.forEach(filter => {
                    filter.addEventListener('change', fetchResults);
                });
            }
        });

        // SWEET ALERT FUNCTION
        function verifyAction(itemId) {
            Swal.fire({
                title: 'Verifikasi Barang',
                text: "Apakah fisik barang ini sesuai dengan laporan?",
                icon: 'question',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Ya, Lolos (Approve)',
                denyButtonText: 'Tidak (Reject)',
                confirmButtonColor: '#10b981', // emerald-500
                denyButtonColor: '#f43f5e', // rose-500
                cancelButtonColor: '#94a3b8',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // ACTION APPROVE
                    document.getElementById('action-input-' + itemId).value = 'approve';
                    document.getElementById('verify-form-' + itemId).submit();
                } else if (result.isDenied) {
                    // ACTION REJECT
                    document.getElementById('action-input-' + itemId).value = 'reject';
                    document.getElementById('verify-form-' + itemId).submit();
                }
            });
        }
    </script>

    <style>.animate-fade-in { animation: fadeIn 0.5s ease-out; } @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }</style>
</x-app-layout>
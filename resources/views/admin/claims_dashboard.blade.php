<x-app-layout>
    <div class="min-h-screen bg-slate-50 font-sans">
        
        <div class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div>
                        <h1 class="text-2xl font-black text-slate-800 tracking-tight">Manajemen Klaim & Serah Terima</h1>
                        <p class="text-xs text-slate-500">Validasi kepemilikan dan proses pengembalian barang.</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-white text-slate-600 text-xs font-bold rounded-xl border border-slate-200 hover:bg-slate-50 transition">
                            &larr; Kembali ke Laporan
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 animate-fade-in">
                <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Klaim Masuk</p>
                    <p class="text-3xl font-black text-slate-800 mt-2">{{ $stats['total'] }}</p>
                </div>

                <a href="{{ route('admin.claims-dashboard', ['filter' => 'pending']) }}" class="relative overflow-hidden bg-yellow-400 p-5 rounded-3xl border border-yellow-500 shadow-lg shadow-yellow-200 group hover:scale-105 transition cursor-pointer text-white flex flex-col justify-between">
                    <div class="absolute -right-4 -top-4 p-4 opacity-20 transform rotate-12 group-hover:rotate-0 transition">
                        <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-yellow-100 uppercase tracking-wider">Menunggu Verifikasi</p>
                        <p class="text-3xl font-black mt-2">{{ $stats['pending'] }}</p>
                    </div>
                    <div class="flex items-center text-[10px] font-bold mt-2 bg-yellow-500/30 w-fit px-2 py-1 rounded-lg backdrop-blur-sm">
                        Filter Pending <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </div>
                </a>

                <a href="{{ route('admin.claims-dashboard', ['filter' => 'ready']) }}" class="relative overflow-hidden bg-emerald-500 p-5 rounded-3xl border border-emerald-600 shadow-lg shadow-emerald-200 group hover:scale-105 transition cursor-pointer text-white flex flex-col justify-between">
                    <div class="absolute -right-4 -top-4 p-4 opacity-20 transform rotate-12 group-hover:rotate-0 transition">
                        <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-emerald-100 uppercase tracking-wider">Siap Diambil</p>
                        <p class="text-3xl font-black mt-2">{{ $stats['verified'] }}</p>
                    </div>
                    <div class="flex items-center text-[10px] font-bold mt-2 bg-emerald-600/30 w-fit px-2 py-1 rounded-lg backdrop-blur-sm">
                        Filter Siap <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </div>
                </a>

                <div class="bg-white p-5 rounded-3xl border border-rose-100 shadow-sm flex flex-col justify-between hover:border-rose-300 transition">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Ditolak</p>
                    <p class="text-3xl font-black text-rose-500 mt-2">{{ $stats['rejected'] }}</p>
                </div>

                <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between hover:border-slate-400 transition">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Selesai (Returned)</p>
                    <p class="text-3xl font-black text-slate-700 mt-2">{{ $stats['completed'] }}</p>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden animate-fade-in" style="animation-delay: 0.1s;">
                <div class="p-6 border-b border-slate-100 bg-slate-50/30">
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-4">
                        <h3 class="font-bold text-lg text-slate-800 flex items-center gap-2">
                            <span class="w-1.5 h-6 bg-yellow-400 rounded-full"></span>
                            Data Klaim Masuk
                        </h3>
                        
                        @if(request('filter'))
                            <a href="{{ route('admin.claims-dashboard') }}" class="text-xs font-bold text-rose-500 bg-rose-50 px-3 py-1.5 rounded-lg hover:bg-rose-100 transition flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                Hapus Filter
                            </a>
                        @endif
                    </div>

                    <form action="{{ route('admin.claims-dashboard') }}" method="GET" class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <select name="per_page" onchange="this.form.submit()" class="text-xs border-slate-200 rounded-xl focus:ring-slate-500 cursor-pointer bg-white">
                            <option value="10">10 Entries</option>
                            <option value="25">25 Entries</option>
                        </select>
                        <select name="status" onchange="this.form.submit()" class="text-xs border-slate-200 rounded-xl focus:ring-slate-500 cursor-pointer bg-white">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending (Perlu Cek)</option>
                            <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified (Siap Diambil)</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                        <div class="col-span-2">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama Barang / Pengklaim..." class="w-full text-xs border-slate-200 rounded-xl focus:ring-slate-500 bg-white">
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Barang</th>
                                <th class="px-6 py-4">Pengklaim</th>
                                <th class="px-6 py-4">Bukti Deskripsi</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($claims as $claim)
                            <tr class="hover:bg-slate-50/80 transition group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $claim->item->image_path ? asset('storage/'.$claim->item->image_path) : 'https://via.placeholder.com/50' }}" class="w-10 h-10 rounded-lg object-cover bg-slate-100 border border-slate-200 shadow-sm">
                                        <p class="font-bold text-slate-800 line-clamp-1 w-32 group-hover:text-yellow-600 transition">{{ $claim->item->title }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs">
                                    <div class="font-bold text-slate-700">{{ $claim->user->name }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $claim->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 text-xs italic text-slate-500 truncate max-w-xs">
                                    "{{ $claim->proof_description }}"
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide border
                                        @if($claim->status == 'pending') bg-yellow-50 text-yellow-700 border-yellow-100
                                        @elseif($claim->status == 'verified') bg-emerald-50 text-emerald-700 border-emerald-100
                                        @elseif($claim->status == 'rejected') bg-rose-50 text-rose-700 border-rose-100
                                        @elseif($claim->status == 'completed') bg-slate-100 text-slate-600 border-slate-200
                                        @endif">
                                        {{ $claim->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div x-data="{ modalOpen: false }">
                                        <button @click="modalOpen = true" class="text-xs font-bold text-white bg-slate-800 px-4 py-2 rounded-xl shadow hover:bg-slate-700 hover:-translate-y-0.5 transition flex items-center gap-2 ml-auto">
                                            <span>Proses</span>
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </button>

                                        <template x-teleport="body">
                                            <div x-show="modalOpen" class="fixed inset-0 z-[99] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4" 
                                                 style="display: none;"
                                                 x-transition:enter="transition ease-out duration-300" 
                                                 x-transition:enter-start="opacity-0" 
                                                 x-transition:enter-end="opacity-100"
                                                 x-transition:leave="transition ease-in duration-200" 
                                                 x-transition:leave-start="opacity-100" 
                                                 x-transition:leave-end="opacity-0">
                                                
                                                <div class="bg-white rounded-[2rem] w-full max-w-4xl overflow-hidden shadow-2xl relative flex flex-col md:flex-row transform transition-all" @click.away="modalOpen = false"
                                                     x-transition:enter="transition ease-out duration-300" 
                                                     x-transition:enter-start="opacity-0 scale-90 translate-y-4" 
                                                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                                     x-transition:leave="transition ease-in duration-200" 
                                                     x-transition:leave-start="opacity-100 scale-100 translate-y-0" 
                                                     x-transition:leave-end="opacity-0 scale-90 translate-y-4">
                                                    
                                                    <button @click="modalOpen = false" class="absolute top-4 right-4 z-50 bg-black/30 hover:bg-black/50 text-white p-2 rounded-full backdrop-blur-md transition">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    </button>

                                                    <div class="w-full md:w-5/12 bg-slate-900 relative min-h-[300px] flex flex-col">
                                                        <div class="flex-1 relative">
                                                            @if($claim->item->image_path) 
                                                                <img src="{{ asset('storage/'.$claim->item->image_path) }}" class="absolute inset-0 w-full h-full object-cover opacity-80"> 
                                                            @else
                                                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-500 bg-slate-100"><span class="text-xs font-bold uppercase">No Image</span></div>
                                                            @endif
                                                            
                                                            <div class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-black/90 to-transparent">
                                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Barang Dilaporkan</p>
                                                                <h3 class="text-xl font-black text-white leading-tight">{{ $claim->item->title }}</h3>
                                                                <p class="text-xs text-slate-300 mt-1 flex items-center">
                                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                                                    {{ $claim->item->location }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="w-full md:w-7/12 p-8 md:p-10 bg-white flex flex-col">
                                                        <div class="mb-6">
                                                            <div class="flex items-center gap-3 mb-4">
                                                                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold text-sm">
                                                                    {{ substr($claim->user->name, 0, 1) }}
                                                                </div>
                                                                <div>
                                                                    <p class="text-sm font-bold text-slate-800">{{ $claim->user->name }}</p>
                                                                    <p class="text-[10px] text-slate-400 uppercase tracking-wide">Pengklaim</p>
                                                                </div>
                                                                <div class="ml-auto">
                                                                    <span class="bg-slate-100 text-slate-500 px-2 py-1 rounded text-[10px] font-bold">{{ $claim->created_at->diffForHumans() }}</span>
                                                                </div>
                                                            </div>

                                                            <div class="bg-yellow-50 p-4 rounded-2xl border border-yellow-100 mb-4">
                                                                <p class="text-[10px] font-bold text-yellow-600 uppercase tracking-wider mb-1">Bukti Deskripsi</p>
                                                                <p class="text-sm text-slate-700 italic leading-relaxed">"{{ $claim->proof_description }}"</p>
                                                            </div>

                                                            @if($claim->proof_file)
                                                                <div class="mt-2">
                                                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Foto Bukti Pengklaim</p>
                                                                    <a href="{{ asset('storage/'.$claim->proof_file) }}" target="_blank" class="block relative group overflow-hidden rounded-xl border border-slate-200">
                                                                        <img src="{{ asset('storage/'.$claim->proof_file) }}" class="w-full h-32 object-cover transition transform group-hover:scale-105">
                                                                        <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                                                            <span class="text-white text-xs font-bold flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> Lihat Full</span>
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                            @else
                                                                <p class="text-xs text-slate-400 italic mt-2">* Tidak ada foto bukti dilampirkan.</p>
                                                            @endif
                                                        </div>

                                                        <div class="mt-auto pt-6 border-t border-slate-100">
                                                            @if($claim->status == 'pending')
                                                                <div class="grid grid-cols-2 gap-3">
                                                                    <form action="{{ route('admin.claims.update', $claim) }}" method="POST">
                                                                        @csrf @method('PATCH')
                                                                        <button name="status" value="rejected" onclick="confirmSubmit(event, 'Tolak klaim ini?')" class="w-full py-3 bg-white border border-rose-200 text-rose-600 font-bold rounded-xl text-sm hover:bg-rose-50 transition">Tolak</button>
                                                                    </form>
                                                                    <form action="{{ route('admin.claims.update', $claim) }}" method="POST">
                                                                        @csrf @method('PATCH')
                                                                        <button name="status" value="verified" onclick="confirmSubmit(event, 'Bukti valid? Terima klaim ini.')" class="w-full py-3 bg-emerald-500 text-white font-bold rounded-xl text-sm shadow-lg shadow-emerald-200 hover:bg-emerald-600 transition hover:-translate-y-0.5">Terima (Valid)</button>
                                                                    </form>
                                                                </div>
                                                            @elseif($claim->status == 'verified')
                                                                <div class="bg-emerald-50 p-3 rounded-xl border border-emerald-100 mb-3 text-center">
                                                                    <p class="text-xs text-emerald-600 font-bold">Klaim sudah diverifikasi. Menunggu pengambilan.</p>
                                                                </div>
                                                                <form action="{{ route('admin.claims.update', $claim) }}" method="POST">
                                                                    @csrf @method('PATCH')
                                                                    <button name="status" value="completed" onclick="confirmSubmit(event, 'Konfirmasi barang sudah diserahkan?')" class="w-full py-3 bg-slate-900 text-white font-bold rounded-xl text-sm shadow-xl hover:bg-slate-800 transition hover:-translate-y-0.5 flex justify-center items-center">
                                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                                        Selesaikan (Barang Diambil)
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <div class="text-center bg-slate-100 py-3 rounded-xl font-bold text-slate-500 text-sm">
                                                                    Status: {{ ucfirst($claim->status) }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-12 text-slate-400 italic">Tidak ada data klaim yang sesuai.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">{{ $claims->links() }}</div>
            </div>
        </div>
    </div>
    <style>.animate-fade-in { animation: fadeIn 0.5s ease-out; } @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }</style>
</x-app-layout>
<x-app-layout>

    <div class="min-h-screen bg-slate-50 font-sans" x-data="{ activeTab: 'items' }">
        
        <div class="bg-white border-b border-slate-200 z-30 relative shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                    <div>
                        <h1 class="text-3xl font-black text-slate-800 tracking-tight">Security Command Center</h1>
                        <p class="text-slate-500 text-sm mt-2">
                            Petugas: <span class="font-bold text-sky-600">{{ Auth::user()->name }}</span> | 
                            <span class="text-xs bg-slate-100 px-2 py-1 rounded text-slate-500 font-medium">{{ now()->translatedFormat('l, d F Y') }}</span>
                        </p>
                    </div>
                    
                    <a href="{{ route('items.create') }}" class="group flex items-center px-6 py-3 bg-slate-900 text-white text-sm font-bold rounded-2xl hover:bg-slate-800 transition shadow-xl hover:shadow-2xl hover:-translate-y-1">
                        <svg class="w-5 h-5 mr-2 text-sky-400 group-hover:text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Input Barang Temuan
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <button @click="activeTab = 'items'" class="text-left relative overflow-hidden bg-white p-5 rounded-3xl border border-slate-100 shadow-sm hover:shadow-lg transition group ring-2 ring-transparent focus:outline-none"
                        :class="activeTab === 'items' ? 'ring-sky-500 bg-sky-50/50' : 'hover:border-sky-200'">
                        <div class="flex items-center justify-between relative z-10">
                            <div>
                                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Verifikasi Fisik</p>
                                <h3 class="text-3xl font-black text-slate-800">{{ $unverifiedItems->count() }} <span class="text-sm font-bold text-slate-400">barang</span></h3>
                            </div>
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center transition-colors" :class="activeTab === 'items' ? 'bg-sky-500 text-white shadow-lg shadow-sky-200' : 'bg-slate-100 text-slate-400 group-hover:bg-sky-100 group-hover:text-sky-500'">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            </div>
                        </div>
                    </button>

                    <button @click="activeTab = 'claims'" class="text-left relative overflow-hidden bg-white p-5 rounded-3xl border border-slate-100 shadow-sm hover:shadow-lg transition group ring-2 ring-transparent focus:outline-none"
                        :class="activeTab === 'claims' ? 'ring-yellow-400 bg-yellow-50/50' : 'hover:border-yellow-200'">
                        <div class="flex items-center justify-between relative z-10">
                            <div>
                                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Klaim Masuk</p>
                                <h3 class="text-3xl font-black text-slate-800">{{ $pendingClaims->count() }} <span class="text-sm font-bold text-slate-400">pending</span></h3>
                            </div>
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center transition-colors" :class="activeTab === 'claims' ? 'bg-yellow-400 text-white shadow-lg shadow-yellow-200' : 'bg-slate-100 text-slate-400 group-hover:bg-yellow-100 group-hover:text-yellow-500'">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            </div>
                        </div>
                    </button>

                    <button @click="activeTab = 'handover'" class="text-left relative overflow-hidden bg-white p-5 rounded-3xl border border-slate-100 shadow-sm hover:shadow-lg transition group ring-2 ring-transparent focus:outline-none"
                        :class="activeTab === 'handover' ? 'ring-emerald-500 bg-emerald-50/50' : 'hover:border-emerald-200'">
                        <div class="flex items-center justify-between relative z-10">
                            <div>
                                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Siap Serah Terima</p>
                                <h3 class="text-3xl font-black text-slate-800">{{ $approvedClaims->count() }} <span class="text-sm font-bold text-slate-400">selesai</span></h3>
                            </div>
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center transition-colors" :class="activeTab === 'handover' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-200' : 'bg-slate-100 text-slate-400 group-hover:bg-emerald-100 group-hover:text-emerald-500'">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                    </button>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            <div x-show="activeTab === 'items'" class="animate-fade-in">
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-slate-800">1. Konfirmasi Fisik Barang</h2>
                    <p class="text-sm text-slate-500">Klik pada kartu barang untuk melihat detail dan melakukan verifikasi.</p>
                </div>

                @if($unverifiedItems->isEmpty())
                    <div class="flex flex-col items-center justify-center py-20 bg-white rounded-[2rem] border-2 border-dashed border-slate-200">
                        <div class="bg-slate-50 p-6 rounded-full mb-4">
                            <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <p class="text-slate-500 font-bold text-lg">Semua barang temuan sudah terverifikasi.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($unverifiedItems as $item)
                        <div x-data="{ detailModalOpen: false }" class="h-full">
                            
                            <div @click="detailModalOpen = true" class="group bg-white rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer h-full flex flex-col overflow-hidden relative">
                                <div class="absolute top-4 left-4 z-10">
                                    <span class="bg-rose-500 text-white text-[10px] font-black px-3 py-1.5 rounded-full uppercase tracking-wider shadow-lg">Butuh Verifikasi</span>
                                </div>

                                <div class="relative h-56 bg-slate-100 overflow-hidden">
                                    @if($item->image_path)
                                        <img src="{{ asset('storage/'.$item->image_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                                    @else
                                        <div class="flex items-center justify-center h-full text-slate-300 bg-slate-50">
                                            <svg class="w-16 h-16 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif
                                    <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/80 to-transparent">
                                        <h3 class="font-bold text-white text-lg leading-tight truncate">{{ $item->title }}</h3>
                                        <p class="text-xs text-slate-300 mt-1 flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                            {{ $item->location }}
                                        </p>
                                    </div>
                                </div>
                                <div class="p-5 flex-1 bg-white">
                                    <div class="flex items-center justify-between text-xs text-slate-500 mb-4">
                                        <span class="bg-slate-50 px-2 py-1 rounded font-bold text-slate-700">{{ $item->user->name ?? 'Anonim' }}</span>
                                        <span>{{ $item->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-sm text-slate-600 line-clamp-3 mb-4">{{ $item->description }}</p>
                                    
                                    <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-center text-sky-600 font-bold text-sm group-hover:text-sky-500 transition">
                                        Klik untuk Detail & Verifikasi
                                        <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </div>
                                </div>
                            </div>

                            <template x-teleport="body">
                                <div x-show="detailModalOpen" class="fixed inset-0 z-[999] overflow-y-auto" style="display: none;">
                                    <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" 
                                        x-show="detailModalOpen"
                                        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                        x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                        @click="detailModalOpen = false"></div>

                                    <div class="flex min-h-screen items-center justify-center p-4">
                                        <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden transform transition-all"
                                            x-show="detailModalOpen"
                                            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-4">

                                            <div class="relative h-64 bg-slate-800">
                                                @if($item->image_path)
                                                    <img src="{{ asset('storage/'.$item->image_path) }}" class="w-full h-full object-cover opacity-80">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-slate-500 bg-slate-200">No Image</div>
                                                @endif
                                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent"></div>
                                                
                                                <button @click="detailModalOpen = false" class="absolute top-4 right-4 bg-black/30 hover:bg-black/50 text-white p-2 rounded-full backdrop-blur-md transition">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                </button>

                                                <div class="absolute bottom-6 left-6 right-6 text-white">
                                                    <span class="bg-rose-500 px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wide mb-2 inline-block">Verifikasi Fisik</span>
                                                    <h2 class="text-3xl font-black leading-tight">{{ $item->title }}</h2>
                                                </div>
                                            </div>

                                            <div class="p-8">
                                                <div class="grid grid-cols-2 gap-6 mb-6">
                                                    <div>
                                                        <label class="text-xs font-bold text-slate-400 uppercase">Kategori</label>
                                                        <p class="font-bold text-slate-800">{{ $item->category->name }}</p>
                                                    </div>
                                                    <div>
                                                        <label class="text-xs font-bold text-slate-400 uppercase">Waktu Lapor</label>
                                                        <p class="font-bold text-slate-800">{{ $item->created_at->format('d M Y, H:i') }} WIB</p>
                                                    </div>
                                                    <div>
                                                        <label class="text-xs font-bold text-slate-400 uppercase">Lokasi Ditemukan</label>
                                                        <p class="font-bold text-slate-800 flex items-center">
                                                            <svg class="w-4 h-4 mr-1 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                                            {{ $item->location }}
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <label class="text-xs font-bold text-slate-400 uppercase">Pelapor</label>
                                                        <p class="font-bold text-slate-800">{{ $item->user->name ?? 'Anonim' }}</p>
                                                    </div>
                                                </div>

                                                <div class="mb-8">
                                                    <label class="text-xs font-bold text-slate-400 uppercase mb-1 block">Deskripsi Barang</label>
                                                    <div class="bg-slate-50 p-4 rounded-xl text-slate-700 text-sm leading-relaxed border border-slate-100">
                                                        {{ $item->description }}
                                                    </div>
                                                </div>

                                                <div class="flex gap-4 border-t border-slate-100 pt-6">
                                                    <button @click="detailModalOpen = false" class="w-1/3 py-3 bg-white text-slate-600 font-bold rounded-xl border border-slate-200 hover:bg-slate-50 transition">
                                                        Batal
                                                    </button>
                                                    
                                                    <form action="{{ route('admin.items.verify', $item) }}" method="POST" class="w-2/3">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="button" onclick="confirmSubmit(event, 'Apakah fisik barang ini sudah benar-benar ada di Pos Keamanan?')" 
                                                                class="w-full py-3 bg-sky-500 text-white font-bold rounded-xl hover:bg-sky-600 hover:shadow-lg hover:shadow-sky-200 transition flex items-center justify-center">
                                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                            Verifikasi: Fisik Diterima
                                                        </button>
                                                    </form>
                                                </div>
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
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-slate-800">2. Validasi Klaim Masuk</h2>
                    <p class="text-sm text-slate-500">Pastikan bukti yang diberikan pelapor cocok dengan fisik barang.</p>
                </div>

                @if($pendingClaims->isEmpty())
                     <div class="flex flex-col items-center justify-center py-20 bg-white rounded-[2rem] border-2 border-dashed border-slate-200">
                        <div class="bg-yellow-50 p-6 rounded-full mb-4">
                            <svg class="w-12 h-12 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-slate-500 font-bold text-lg">Tidak ada klaim pending saat ini.</p>
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach($pendingClaims as $claim)
                        <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm hover:shadow-md transition duration-300">
                            <div class="flex flex-col lg:flex-row gap-8">
                                <div class="lg:w-1/4">
                                    <div class="relative rounded-2xl overflow-hidden aspect-square bg-slate-100 mb-4 shadow-inner">
                                        @if($claim->item->image_path)
                                            <img src="{{ asset('storage/'.$claim->item->image_path) }}" class="w-full h-full object-cover">
                                        @endif
                                        <div class="absolute bottom-0 left-0 right-0 bg-black/60 backdrop-blur-sm text-white text-xs font-bold px-3 py-2 text-center">
                                            ID Barang: #{{ $claim->item->id }}
                                        </div>
                                    </div>
                                    <h4 class="font-bold text-slate-800 text-center leading-tight">{{ $claim->item->title }}</h4>
                                </div>

                                <div class="lg:w-2/4 border-l border-slate-100 lg:pl-8 flex flex-col justify-center">
                                    <div class="flex items-center gap-4 mb-6">
                                        <div class="w-12 h-12 rounded-full bg-sky-100 text-sky-600 flex items-center justify-center font-black text-xl">
                                            {{ substr($claim->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-400 uppercase font-bold tracking-wider">Pengklaim</p>
                                            <p class="font-bold text-slate-800 text-lg">{{ $claim->user->name }}</p>
                                            <p class="text-sm text-slate-500">{{ $claim->user->email }}</p>
                                        </div>
                                        <div class="ml-auto">
                                            <span class="bg-yellow-100 text-yellow-700 text-xs font-bold px-3 py-1 rounded-full">{{ $claim->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>

                                    <div class="bg-yellow-50 p-5 rounded-2xl border border-yellow-100 relative">
                                        <div class="absolute -top-3 left-4 bg-yellow-400 text-white text-[10px] font-bold px-2 py-0.5 rounded uppercase">Bukti Kepemilikan</div>
                                        <p class="text-slate-700 italic text-sm mt-1">"{{ $claim->proof_description }}"</p>
                                        
                                        @if($claim->proof_file)
                                            <div class="mt-3 pt-3 border-t border-yellow-200/50">
                                                <a href="{{ asset('storage/'.$claim->proof_file) }}" target="_blank" class="inline-flex items-center text-xs font-bold text-sky-600 hover:text-sky-700 hover:underline">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                    Lihat Foto Lampiran
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="lg:w-1/4 flex flex-col justify-center gap-4 lg:border-l lg:border-slate-100 lg:pl-8">
                                    <form action="{{ route('admin.claims.update', $claim) }}" method="POST" class="w-full space-y-4">
                                        @csrf
                                        @method('PATCH')
                                        
                                        <div>
                                            <label class="text-xs font-bold text-slate-400 uppercase mb-1 block">Catatan (Opsional)</label>
                                            <input type="text" name="notes" class="w-full text-sm border-slate-200 rounded-xl focus:ring-sky-500 focus:border-sky-500 bg-slate-50" placeholder="Contoh: Ambil di Pos 1...">
                                        </div>

                                        <button type="submit" name="status" value="verified" onclick="confirmSubmit(event, 'Setujui klaim ini? Barang akan ditandai sebagai claimed.')"
                                            class="w-full py-3 bg-emerald-500 text-white font-bold rounded-xl hover:bg-emerald-600 transition text-sm shadow-lg shadow-emerald-200">
                                            Terima Klaim
                                        </button>
                                        <button type="submit" name="status" value="rejected" onclick="confirmSubmit(event, 'Tolak klaim ini?')"
                                            class="w-full py-3 bg-white text-rose-500 border-2 border-rose-100 font-bold rounded-xl hover:bg-rose-50 transition text-sm">
                                            Tolak Klaim
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div x-show="activeTab === 'handover'" style="display: none;" class="animate-fade-in">
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-slate-800">3. Serah Terima Barang</h2>
                    <p class="text-sm text-slate-500">Konfirmasi akhir saat pemilik datang mengambil barang.</p>
                </div>

                @if($approvedClaims->isEmpty())
                    <div class="flex flex-col items-center justify-center py-20 bg-white rounded-[2rem] border-2 border-dashed border-slate-200">
                        <div class="bg-emerald-50 p-6 rounded-full mb-4">
                            <svg class="w-12 h-12 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-slate-500 font-bold text-lg">Belum ada barang yang siap diambil.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($approvedClaims as $claim)
                        <div class="bg-white rounded-3xl border border-emerald-100 shadow-[0_10px_40px_-10px_rgba(16,185,129,0.1)] p-8 relative overflow-hidden group hover:scale-[1.01] transition duration-300">
                            <div class="absolute -right-10 -top-10 w-32 h-32 bg-emerald-50 rounded-full group-hover:bg-emerald-100 transition duration-500"></div>
                            
                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-6">
                                    <span class="bg-emerald-100 text-emerald-700 text-xs font-black px-3 py-1.5 rounded-lg uppercase tracking-wider flex items-center">
                                        <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2 animate-pulse"></span>
                                        Siap Diambil
                                    </span>
                                    <span class="text-xs text-slate-400 font-mono">ID: #{{ $claim->id }}</span>
                                </div>

                                <div class="flex gap-5 items-start mb-8">
                                    <img src="{{ $claim->item->image_path ? asset('storage/'.$claim->item->image_path) : 'https://via.placeholder.com/150' }}" class="w-24 h-24 rounded-2xl object-cover bg-slate-100 shadow-md">
                                    <div>
                                        <h4 class="font-black text-slate-800 text-xl leading-tight mb-2">{{ $claim->item->title }}</h4>
                                        <div class="space-y-1">
                                            <p class="text-sm text-slate-500">Pemilik: <span class="text-sky-600 font-bold">{{ $claim->user->name }}</span></p>
                                            <p class="text-xs text-slate-400">{{ $claim->user->email }}</p>
                                        </div>
                                    </div>
                                </div>

                                <form action="{{ route('admin.claims.update', $claim) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" name="status" value="completed" onclick="confirmSubmit(event, 'Konfirmasi: Barang sudah diserahkan ke pemilik dan kasus ditutup?')" 
                                        class="w-full py-4 bg-slate-900 text-white font-bold rounded-2xl hover:bg-emerald-600 hover:shadow-xl hover:shadow-emerald-200 transition flex items-center justify-center group-hover:translate-y-0">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Barang Sudah Diserahkan (Selesai)
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>

    <style>
        .animate-fade-in { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</x-app-layout>
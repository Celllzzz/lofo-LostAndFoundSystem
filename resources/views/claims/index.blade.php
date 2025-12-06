<x-app-layout>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
        .no-arrow select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }
    </style>

    <div class="min-h-screen bg-orange-50/50"
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

        <div class="bg-white border-b border-orange-100 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <h1 class="text-2xl font-bold text-slate-800">Riwayat Klaim Saya</h1>
                <p class="text-slate-500 text-sm mt-1">Pantau status verifikasi kepemilikan barang temuan Anda di sini.</p>
            </div>
        </div>

        <div class="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-orange-100 shadow-sm transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <form x-ref="filterForm" action="{{ route('claims.index') }}" method="GET" 
                      class="flex flex-col lg:flex-row justify-between items-center gap-4 animate-fade-in">
                    
                    <div class="flex items-center space-x-2 w-full lg:w-auto order-2 lg:order-1 justify-center lg:justify-start">
                        <span class="text-xs font-semibold text-slate-500">Show</span>
                        <div class="relative no-arrow">
                            <select name="per_page" onchange="this.form.submit()" 
                                class="pl-3 pr-8 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-700 focus:ring-2 focus:ring-sky-200 focus:border-sky-400 cursor-pointer shadow-sm hover:border-sky-300 transition-colors">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-400">
                            </div>
                        </div>
                        <span class="text-xs font-semibold text-slate-500">entries</span>
                    </div>

                    <div class="flex flex-wrap justify-center gap-2 w-full lg:w-auto order-1 lg:order-2">
                        <button type="submit" name="status" value="" 
                            class="px-4 py-1.5 text-xs font-bold rounded-full transition-all duration-300 {{ !request('status') ? 'bg-slate-800 text-white shadow-md transform scale-105' : 'bg-white text-slate-500 border border-slate-200 hover:bg-slate-50 hover:text-slate-700' }}">
                            Semua
                        </button>
                        <button type="submit" name="status" value="pending" 
                            class="px-4 py-1.5 text-xs font-bold rounded-full transition-all duration-300 {{ request('status') == 'pending' ? 'bg-yellow-400 text-yellow-900 shadow-md shadow-yellow-100 transform scale-105' : 'bg-white text-slate-500 border border-slate-200 hover:bg-yellow-50 hover:text-yellow-600' }}">
                            Pending
                        </button>
                        <button type="submit" name="status" value="verified" 
                            class="px-4 py-1.5 text-xs font-bold rounded-full transition-all duration-300 {{ request('status') == 'verified' ? 'bg-emerald-500 text-white shadow-md shadow-emerald-200 transform scale-105' : 'bg-white text-slate-500 border border-slate-200 hover:bg-emerald-50 hover:text-emerald-600' }}">
                            Diterima
                        </button>
                        <button type="submit" name="status" value="rejected" 
                            class="px-4 py-1.5 text-xs font-bold rounded-full transition-all duration-300 {{ request('status') == 'rejected' ? 'bg-rose-500 text-white shadow-md shadow-rose-200 transform scale-105' : 'bg-white text-slate-500 border border-slate-200 hover:bg-rose-50 hover:text-rose-600' }}">
                            Ditolak
                        </button>
                    </div>

                    <div class="w-full lg:w-72 relative order-3">
                        <div class="relative group">
                            <input type="text" name="search" x-model="search" @input="submitSearch()" placeholder="Cari nama barang..." 
                                   class="w-full pl-10 pr-10 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:border-sky-400 rounded-full text-sm transition-all duration-300 shadow-sm focus:shadow-md focus:ring-4 focus:ring-sky-50 placeholder-slate-400 text-slate-700 group-hover:bg-white group-hover:border-sky-200">
                            
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors duration-300" :class="isLoading ? 'text-sky-500' : 'text-slate-400 group-hover:text-sky-500'">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center" x-show="isLoading" style="display: none;">
                                <svg class="animate-spin h-4 w-4 text-sky-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 min-h-[60vh]">

            @if(request('search') || request('status'))
                <div class="mb-6 flex flex-col items-center justify-center animate-fade-in">
                    <p class="text-slate-500 text-sm bg-white px-4 py-1 rounded-full shadow-sm border border-slate-100">
                        Menampilkan <span class="font-bold text-slate-800">{{ $claims->count() }}</span> dari <span class="font-bold text-slate-800">{{ $claims->total() }}</span> data klaim
                    </p>
                    <a href="{{ route('claims.index') }}" class="mt-2 text-xs font-bold text-rose-500 hover:text-rose-600 hover:underline transition">Reset Filter</a>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-[0_2px_8px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden animate-fade-in">
                
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-100">
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider w-16 text-center">No</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider min-w-[250px]">Detail Barang</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider min-w-[150px]">Tanggal Klaim</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider min-w-[200px]">Bukti Kepemilikan</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider w-32 text-center">Status</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider min-w-[200px]">Catatan Admin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($claims as $index => $claim)
                            <tr class="hover:bg-slate-50/50 transition-colors duration-150 group">
                                
                                <td class="px-6 py-4 text-center text-sm text-slate-400 font-bold">
                                    {{ $claims->firstItem() + $index }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-12 w-12 flex-shrink-0 bg-slate-100 rounded-lg overflow-hidden border border-slate-200">
                                            @if($claim->item->image_path)
                                                <img class="h-full w-full object-cover" src="{{ asset('storage/'.$claim->item->image_path) }}" alt="">
                                            @else
                                                <div class="flex items-center justify-center h-full text-slate-300">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-slate-800 group-hover:text-sky-600 transition">{{ $claim->item->title }}</div>
                                            <div class="text-xs text-slate-500 flex items-center mt-1">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                {{ $claim->item->location }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-700">
                                            {{ $claim->created_at->timezone('Asia/Jakarta')->format('d M Y') }}
                                        </span>
                                        <span class="text-xs text-slate-400">
                                            Pukul {{ $claim->created_at->timezone('Asia/Jakarta')->format('H:i') }} WIB
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <p class="text-sm text-slate-600 line-clamp-2 mb-1" title="{{ $claim->proof_description }}">
                                        {{ $claim->proof_description }}
                                    </p>
                                    @if($claim->proof_file)
                                        <a href="{{ asset('storage/'.$claim->proof_file) }}" target="_blank" class="inline-flex items-center text-xs font-bold text-sky-500 hover:text-sky-700 bg-sky-50 hover:bg-sky-100 px-2 py-1 rounded-md transition">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                            Lihat Foto Bukti
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Tidak ada lampiran file</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if($claim->status == 'pending')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 mr-1.5 animate-pulse"></span>
                                            Pending
                                        </span>
                                    @elseif($claim->status == 'verified')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            Diterima
                                        </span>
                                    @elseif($claim->status == 'rejected')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            Ditolak
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    @if($claim->verification_notes)
                                        <div class="bg-slate-50 p-2 rounded-lg border border-slate-100 text-xs text-slate-600">
                                            <span class="font-bold text-slate-700 block mb-1">Pesan Petugas:</span>
                                            "{{ $claim->verification_notes }}"
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center bg-white">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="bg-orange-50 p-4 rounded-full mb-3">
                                            <svg class="w-12 h-12 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <h3 class="text-lg font-bold text-slate-700">Data tidak ditemukan</h3>
                                        <p class="text-slate-500 text-sm">Belum ada riwayat klaim yang sesuai dengan pencarian Anda.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8 flex justify-center animate-fade-in">
                {{ $claims->onEachSide(1)->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
        .no-arrow select { -webkit-appearance: none; -moz-appearance: none; appearance: none; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
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
        
        <div class="sticky top-0 z-40 bg-white/80 backdrop-blur-md border-b border-orange-100 shadow-sm transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <form x-ref="filterForm" action="{{ route('items.index') }}" method="GET" class="flex flex-col lg:flex-row justify-between items-center gap-4 animate-fade-in">
                    
                    <div class="flex items-center space-x-2 w-full lg:w-auto order-2 lg:order-1 justify-center lg:justify-start">
                        <span class="text-xs font-semibold text-slate-500">Show</span>
                        <div class="relative no-arrow">
                            <select name="per_page" onchange="this.form.submit()" class="pl-3 pr-8 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-700 focus:ring-2 focus:ring-sky-200 focus:border-sky-400 cursor-pointer shadow-sm hover:border-sky-300">
                                <option value="12" {{ request('per_page') == 12 ? 'selected' : '' }}>12</option>
                                <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }}>24</option>
                                <option value="48" {{ request('per_page') == 48 ? 'selected' : '' }}>48</option>
                            </select>
                        </div>
                        <span class="text-xs font-semibold text-slate-500">entries</span>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto justify-center order-1 lg:order-2">
                        <div class="bg-slate-100/80 p-1 rounded-full flex shadow-inner">
                            <button type="submit" name="type" value="" class="px-5 py-1.5 text-xs font-bold rounded-full transition-all {{ !request('type') ? 'bg-white text-slate-800 shadow-sm ring-1 ring-slate-200' : 'text-slate-400 hover:text-slate-600 hover:bg-slate-200/50' }}">Semua</button>
                            <button type="submit" name="type" value="lost" class="px-5 py-1.5 text-xs font-bold rounded-full transition-all {{ request('type') == 'lost' ? 'bg-rose-500 text-white shadow-md transform scale-105' : 'text-slate-400 hover:text-rose-500 hover:bg-rose-50' }}">Hilang</button>
                            <button type="submit" name="type" value="found" class="px-5 py-1.5 text-xs font-bold rounded-full transition-all {{ request('type') == 'found' ? 'bg-emerald-500 text-white shadow-md transform scale-105' : 'text-slate-400 hover:text-emerald-500 hover:bg-emerald-50' }}">Temuan</button>
                        </div>

                        <div class="relative w-full sm:w-48 group">
                            <select name="category_id" onchange="this.form.submit()" class="w-full pl-4 pr-8 py-2 bg-white border border-slate-200 rounded-full text-xs font-semibold text-slate-600 focus:ring-2 focus:ring-sky-100 focus:border-sky-400 hover:border-sky-300 cursor-pointer shadow-sm appearance-none">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div class="w-full lg:w-72 relative order-3">
                        <div class="relative group">
                            <input type="text" name="search" x-model="search" @input="submitSearch()" placeholder="Cari nama barang..." class="w-full pl-10 pr-10 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:border-sky-400 rounded-full text-sm shadow-sm focus:shadow-md focus:ring-4 focus:ring-sky-50 placeholder-slate-400 text-slate-700">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none" :class="isLoading ? 'text-sky-500' : 'text-slate-400 group-hover:text-sky-500'">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 min-h-[60vh]">
            
            @if(request('search') || request('category_id') || request('type'))
                <div class="mb-6 flex flex-col items-center justify-center animate-fade-in">
                    <p class="text-slate-500 text-sm bg-white px-4 py-1 rounded-full shadow-sm border border-slate-100">
                        Menampilkan <span class="font-bold text-slate-800">{{ $items->count() }}</span> dari <span class="font-bold text-slate-800">{{ $items->total() }}</span> data
                    </p>
                    <a href="{{ route('items.index') }}" class="mt-2 text-xs font-bold text-rose-500 hover:text-rose-600 hover:underline transition">Reset Filter</a>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($items as $item)
                
                <div x-data="{ 
                        modalOpen: false,
                        mode: 'detail', // 'detail' | 'claim'
                        photoPreview: null,
                        fileName: null,
                        
                        openDetail() { this.mode = 'detail'; this.modalOpen = true; },
                        openClaim() { this.mode = 'claim'; },
                        
                        // Logic Upload Foto yang lebih stabil
                        handleFileUpload(e) {
                            const file = e.target.files[0];
                            if (!file) return;
                            this.fileName = file.name;
                            const reader = new FileReader();
                            reader.onload = (event) => {
                                this.photoPreview = event.target.result;
                            };
                            reader.readAsDataURL(file);
                        },
                        clearPreview() {
                            this.photoPreview = null;
                            this.fileName = null;
                            // Reset input file value
                            if(this.$refs.photoInput) this.$refs.photoInput.value = '';
                        },
                        triggerInput() {
                            this.$refs.photoInput.click();
                        }
                     }" 
                     class="group relative bg-white rounded-2xl shadow-[0_2px_8px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_24px_rgb(0,0,0,0.08)] hover:-translate-y-1 transition-all duration-300 border border-slate-100 overflow-hidden flex flex-col h-full animate-fade-in">
                    
                    <div @click="openDetail()" class="relative h-52 bg-slate-50 overflow-hidden cursor-pointer">
                        @if($item->image_path)
                            <img src="{{ asset('storage/'.$item->image_path) }}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700 ease-out">
                        @else
                            <div class="flex flex-col items-center justify-center h-full text-slate-300 group-hover:text-slate-400 transition-colors">
                                <svg class="w-12 h-12 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="text-xs font-medium">No Image</span>
                            </div>
                        @endif
                        <div class="absolute top-3 left-3 flex gap-2">
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider shadow-sm backdrop-blur-md {{ $item->type == 'lost' ? 'bg-rose-500' : 'bg-emerald-500' }} text-white">
                                {{ $item->type == 'lost' ? 'HILANG' : 'TEMUAN' }}
                            </span>
                        </div>
                    </div>

                    <div @click="openDetail()" class="p-4 flex-1 flex flex-col cursor-pointer">
                        <div class="flex justify-between items-center mb-2">
                             <span class="text-[10px] font-bold tracking-wide text-sky-600 bg-sky-50 border border-sky-100 px-2 py-0.5 rounded-md uppercase">{{ $item->category->name }}</span>
                             <span class="text-[10px] text-slate-400 font-medium bg-slate-50 px-2 py-0.5 rounded-full">{{ $item->date->diffForHumans() }}</span>
                        </div>
                        <h3 class="font-bold text-slate-800 text-base leading-snug line-clamp-2 group-hover:text-sky-600 transition-colors mb-2">{{ $item->title }}</h3>
                        <div class="mt-auto flex items-start text-xs text-slate-500 bg-slate-50 p-2 rounded-lg group-hover:bg-sky-50/50 transition-colors">
                            <svg class="w-4 h-4 mr-1.5 text-slate-400 shrink-0 mt-0.5 group-hover:text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            <span class="line-clamp-1 font-medium">{{ $item->location }}</span>
                        </div>
                        <div class="h-0 group-hover:h-8 transition-all duration-300 overflow-hidden opacity-0 group-hover:opacity-100 mt-2 flex items-center justify-center text-sky-500 text-xs font-bold">
                            Klik untuk detail
                        </div>
                    </div>

                    <template x-teleport="body">
                        <div x-show="modalOpen" class="fixed inset-0 z-[999] overflow-y-auto" style="display: none;">
                            <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-md transition-opacity" 
                                 x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                 @click="modalOpen = false"></div>

                            <div class="flex min-h-screen items-center justify-center p-4">
                                <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden transform transition-all"
                                     x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-4">
                                    
                                    <button @click="modalOpen = false" class="absolute top-4 right-4 bg-black/30 hover:bg-black/50 text-white p-2 rounded-full backdrop-blur-md transition z-50">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>

                                    <div x-show="mode === 'detail'" class="animate-fade-in">
                                        <div class="relative h-64 bg-slate-800">
                                            @if($item->image_path)
                                                <img src="{{ asset('storage/'.$item->image_path) }}" class="w-full h-full object-cover opacity-90">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-slate-500 bg-slate-200">No Image</div>
                                            @endif
                                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent"></div>
                                            <div class="absolute bottom-6 left-6 right-6 text-white z-10">
                                                <span class="px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wide mb-2 inline-block {{ $item->type == 'lost' ? 'bg-rose-500' : 'bg-emerald-500' }}">{{ $item->type == 'lost' ? 'HILANG' : 'TEMUAN' }}</span>
                                                <h2 class="text-3xl font-black leading-tight line-clamp-2">{{ $item->title }}</h2>
                                            </div>
                                        </div>

                                        <div class="p-8">
                                            <div class="grid grid-cols-2 gap-6 mb-6">
                                                <div><label class="text-xs font-bold text-slate-400 uppercase">Kategori</label><p class="font-bold text-slate-800">{{ $item->category->name }}</p></div>
                                                <div><label class="text-xs font-bold text-slate-400 uppercase">Waktu</label><p class="font-bold text-slate-800">{{ $item->created_at->format('d M Y, H:i') }}</p></div>
                                                <div class="col-span-2"><label class="text-xs font-bold text-slate-400 uppercase">Lokasi</label><p class="font-bold text-slate-800">{{ $item->location }}</p></div>
                                            </div>
                                            <div class="mb-8 bg-slate-50 p-4 rounded-xl text-slate-700 text-sm border border-slate-100 max-h-40 overflow-y-auto">
                                                {{ $item->description }}
                                            </div>

                                            <div class="border-t border-slate-100 pt-6">
                                                @if($item->type == 'found' && $item->status == 'open' && $item->user_id != auth()->id())
                                                    <button @click="openClaim()" class="w-full py-4 bg-gradient-to-r from-sky-500 to-sky-600 text-white font-bold rounded-2xl hover:shadow-lg hover:shadow-sky-200 transition transform hover:-translate-y-0.5 flex items-center justify-center">
                                                        Saya Pemiliknya (Ajukan Klaim)
                                                    </button>
                                                @elseif($item->user_id == auth()->id())
                                                    <div class="text-center text-sm text-slate-400 font-medium bg-slate-50 py-3 rounded-xl">Anda adalah pelapor barang ini.</div>
                                                @elseif($item->status == 'claimed')
                                                    <div class="bg-slate-100 text-slate-500 font-bold text-center py-3 rounded-xl">Barang sedang dalam proses klaim.</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div x-show="mode === 'claim'" class="animate-fade-in" style="display: none;">
                                        <div class="bg-gradient-to-r from-sky-500 to-emerald-500 p-8 text-white relative">
                                            <button @click="mode = 'detail'" class="absolute top-6 left-6 text-white/80 hover:text-white flex items-center text-sm font-bold transition">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg> Kembali
                                            </button>
                                            <div class="mt-8">
                                                <h2 class="text-2xl font-black">Ajukan Klaim</h2>
                                                <p class="text-sky-50 text-sm mt-1">Isi bukti untuk: <b>{{ $item->title }}</b></p>
                                            </div>
                                        </div>

                                        <form action="{{ route('claims.store', $item) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                                            @csrf
                                            
                                            <div>
                                                <label class="block text-sm font-bold text-slate-700 mb-2">Ciri Unik / Isi Barang <span class="text-rose-500">*</span></label>
                                                <textarea name="proof_description" required rows="3" class="w-full rounded-xl border-slate-200 focus:border-sky-500 focus:ring-sky-500 text-slate-700 text-sm bg-slate-50 placeholder-slate-400" placeholder="Jelaskan ciri khusus yang membuktikan barang ini milik Anda..."></textarea>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-bold text-slate-700 mb-2">Foto Bukti (Wajib) <span class="text-rose-500">*</span></label>
                                                
                                                <input type="file" name="proof_file" x-ref="photoInput" required class="hidden" accept="image/*" @change="handleFileUpload($event)">

                                                <div @click="triggerInput()" 
                                                     class="relative w-full h-40 border-2 border-dashed rounded-2xl cursor-pointer transition-all flex flex-col items-center justify-center overflow-hidden"
                                                     :class="photoPreview ? 'border-sky-500 bg-sky-50' : 'border-slate-300 bg-slate-50 hover:bg-sky-50 hover:border-sky-400'">
                                                    
                                                    <div x-show="!photoPreview" class="flex flex-col items-center pointer-events-none">
                                                        <div class="p-2 bg-white rounded-full shadow-sm mb-2"><svg class="w-6 h-6 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
                                                        <p class="text-sm text-slate-600 font-bold">Klik untuk Upload</p>
                                                    </div>

                                                    <div x-show="photoPreview" class="absolute inset-0 p-2 w-full h-full" style="display: none;">
                                                        <img :src="photoPreview" class="w-full h-full object-contain rounded-xl bg-white border border-slate-200">
                                                        <button type="button" @click.stop="clearPreview()" class="absolute top-3 right-3 bg-rose-500 hover:bg-rose-600 text-white p-1.5 rounded-full shadow-md transition z-20">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                        </button>
                                                    </div>
                                                </div>
                                                <p class="mt-2 text-[10px] text-center" :class="fileName ? 'text-emerald-600 font-bold' : 'text-slate-400'">
                                                    <span x-text="fileName ? 'File Terpilih: ' + fileName : 'Format: JPG, PNG (Max 2MB)'"></span>
                                                </p>
                                            </div>

                                            <button type="submit" class="w-full py-3.5 px-4 bg-sky-500 hover:bg-sky-600 text-white font-bold rounded-xl shadow-lg shadow-sky-200 transition transform hover:-translate-y-0.5 text-sm">
                                                Kirim Klaim Saya
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                @empty
                <div class="col-span-full flex flex-col items-center justify-center py-20 text-center animate-fade-in"><div class="bg-orange-50 p-6 rounded-full mb-4"><svg class="w-16 h-16 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg></div><h3 class="text-xl font-bold text-slate-700">Tidak ada barang ditemukan</h3></div>
                @endforelse
            </div>

            <div class="mt-10 flex justify-center animate-fade-in">{{ $items->onEachSide(1)->links() }}</div>
        </div>
    </div>
</x-app-layout>
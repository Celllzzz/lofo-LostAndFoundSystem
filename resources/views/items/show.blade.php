<x-app-layout>
    <div x-data="{ 
            claimModalOpen: false, 
            photoPreview: null,
            fileName: null,
            updatePreview() {
                const file = this.$refs.photo.files[0];
                if (file) {
                    this.fileName = file.name;
                    const reader = new FileReader();
                    reader.onload = (e) => { this.photoPreview = e.target.result; };
                    reader.readAsDataURL(file);
                }
            },
            clearPreview() {
                this.photoPreview = null;
                this.fileName = null;
                this.$refs.photo.value = null;
            }
         }" 
         class="min-h-screen relative">

        <div class="fixed inset-0 bg-orange-50/50 -z-10"></div>
        <div class="fixed top-0 right-0 -mt-20 -mr-20 w-96 h-96 rounded-full bg-sky-100 blur-3xl opacity-30 -z-10"></div>
        <div class="fixed bottom-0 left-0 -mb-20 -ml-20 w-80 h-80 rounded-full bg-orange-100 blur-3xl opacity-30 -z-10"></div>

        <div class="py-8 sm:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <nav class="flex mb-6" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('items.index') }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-sky-600 transition">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                                Beranda
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-slate-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                                <span class="ml-1 text-sm font-medium text-slate-400">Detail Laporan</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <div class="bg-white rounded-[2.5rem] shadow-xl border border-white/50 overflow-hidden backdrop-blur-sm relative">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-0">
                        
                        <div class="lg:col-span-7 bg-slate-100 relative min-h-[400px] lg:min-h-[600px] group overflow-hidden">
                            @if($item->image_path)
                                <img src="{{ asset('storage/'.$item->image_path) }}" 
                                     class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" 
                                     alt="{{ $item->title }}">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent opacity-60"></div>
                            @else
                                <div class="flex flex-col items-center justify-center h-full text-slate-300 bg-slate-50">
                                    <svg class="w-24 h-24 mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-lg font-medium text-slate-400">Tidak ada foto tersedia</span>
                                </div>
                            @endif

                            <div class="absolute top-6 left-6 flex flex-col gap-2">
                                <span class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg backdrop-blur-md border border-white/20
                                    {{ $item->type == 'lost' ? 'bg-rose-500 text-white' : 'bg-emerald-500 text-white' }}">
                                    {{ $item->type == 'lost' ? 'BARANG HILANG' : 'BARANG TEMUAN' }}
                                </span>
                                
                                @if($item->status == 'claimed')
                                    <span class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg backdrop-blur-md border border-white/20 bg-slate-800 text-white">
                                        SUDAH DIKLAIM
                                    </span>
                                @elseif($item->status == 'pending')
                                    <span class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg backdrop-blur-md border border-white/20 bg-yellow-400 text-yellow-900">
                                        PROSES VERIFIKASI
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="lg:col-span-5 p-8 lg:p-12 flex flex-col h-full bg-white relative">
                            
                            <div class="mb-6">
                                <div class="flex items-center space-x-3 mb-4">
                                    <span class="px-3 py-1 bg-sky-50 text-sky-600 border border-sky-100 text-xs rounded-full font-bold uppercase tracking-wide">
                                        {{ $item->category->name }}
                                    </span>
                                    <span class="text-slate-400 text-xs font-medium flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $item->date->format('d M Y, H:i') }} WIB
                                    </span>
                                </div>

                                <h1 class="text-3xl lg:text-4xl font-black text-slate-800 leading-tight mb-2">
                                    {{ $item->title }}
                                </h1>
                                
                                <div class="flex items-start text-slate-500 mt-3 bg-slate-50 p-3 rounded-xl border border-slate-100">
                                    <svg class="w-5 h-5 mr-2 text-rose-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <span class="font-medium text-sm">{{ $item->location }}</span>
                                </div>
                            </div>

                            <div class="prose prose-slate prose-sm max-w-none text-slate-600 mb-8 flex-grow">
                                <h3 class="text-slate-900 font-bold text-lg mb-2">Deskripsi</h3>
                                <p class="whitespace-pre-line leading-relaxed">{{ $item->description }}</p>
                            </div>

                            <div class="mt-auto pt-6 border-t border-slate-100">
                                @if($item->type == 'found' && $item->status == 'open' && $item->user_id != auth()->id())
                                    <button @click="claimModalOpen = true" 
                                        class="group w-full relative flex items-center justify-center py-4 px-6 bg-slate-900 text-white rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-2xl hover:shadow-sky-200 hover:-translate-y-1">
                                        <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-sky-500 to-emerald-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                        <div class="relative flex items-center font-bold text-lg tracking-wide">
                                            <svg class="w-6 h-6 mr-2 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            INI BARANG SAYA (KLAIM)
                                        </div>
                                    </button>
                                @elseif($item->status == 'claimed')
                                    <div class="bg-slate-100 border border-slate-200 rounded-2xl p-4 text-center">
                                        <p class="text-slate-500 font-bold text-sm">Barang dalam proses klaim.</p>
                                    </div>
                                @elseif($item->user_id == auth()->id())
                                    <div class="bg-sky-50 border border-sky-100 rounded-2xl p-4 text-center">
                                        <p class="text-sky-600 font-bold text-sm">Ini adalah laporan Anda sendiri.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <template x-teleport="body">
            <div x-show="claimModalOpen" 
                 class="fixed inset-0 z-[999] overflow-y-auto" 
                 style="display: none;">
                
                <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-md transition-opacity" 
                     x-show="claimModalOpen"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="claimModalOpen = false"></div>

                <div class="flex min-h-screen items-center justify-center p-4">
                    <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden transform transition-all"
                         x-show="claimModalOpen"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 translate-y-4">

                        <div class="bg-gradient-to-r from-sky-500 to-emerald-500 p-6 sm:p-8 text-white relative">
                            <button @click="claimModalOpen = false" class="absolute top-6 right-6 text-white/70 hover:text-white transition rounded-full hover:bg-white/20 p-1">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                            
                            <h2 class="text-2xl sm:text-3xl font-black mb-2">Konfirmasi Kepemilikan</h2>
                            <p class="text-sky-50 text-sm sm:text-base">Lengkapi data di bawah ini untuk membuktikan barang ini milik Anda.</p>
                        </div>

                        <form action="{{ route('claims.store', $item) }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
                            @csrf
                            
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">
                                    Detail / Ciri Unik <span class="text-rose-500">*</span>
                                </label>
                                <textarea name="proof_description" required rows="4"
                                    class="w-full rounded-xl border-slate-200 focus:border-sky-500 focus:ring-sky-500 text-slate-700 text-sm bg-slate-50 placeholder-slate-400 transition"
                                    placeholder="Jelaskan ciri khusus yang tidak terlihat di foto. Contoh: Isi dompet Rp 50.000, ada bekas goresan di layar bawah, dll."></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">
                                    Bukti Foto / Dokumen <span class="text-rose-500">*</span>
                                </label>
                                
                                <div class="relative group">
                                    <label class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed rounded-2xl cursor-pointer transition-all duration-300"
                                           :class="photoPreview ? 'border-sky-500 bg-sky-50' : 'border-slate-300 bg-slate-50 hover:bg-sky-50 hover:border-sky-400'">
                                        
                                        <div x-show="!photoPreview" class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <div class="p-3 bg-white rounded-full shadow-sm mb-3 group-hover:scale-110 transition-transform">
                                                <svg class="w-8 h-8 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                            <p class="mb-1 text-sm text-slate-600 font-bold">Klik untuk upload bukti</p>
                                            <p class="text-xs text-slate-400">Struk, foto lama, atau kartu identitas</p>
                                        </div>

                                        <div x-show="photoPreview" class="absolute inset-0 w-full h-full p-2" style="display: none;">
                                            <img :src="photoPreview" class="w-full h-full object-contain rounded-xl bg-white border border-slate-200">
                                            
                                            <button type="button" @click.prevent="clearPreview()" class="absolute top-4 right-4 bg-rose-500 text-white p-1.5 rounded-full hover:bg-rose-600 shadow-lg transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </div>

                                        <input type="file" 
                                               name="proof_file" 
                                               required
                                               class="hidden" 
                                               x-ref="photo"
                                               @change="updatePreview()"
                                               accept="image/*" />
                                    </label>
                                </div>
                                <p class="mt-2 text-xs text-center" :class="fileName ? 'text-emerald-600 font-bold' : 'text-slate-400'">
                                    <span x-text="fileName ? 'File terpilih: ' + fileName : 'Wajib menyertakan foto bukti'"></span>
                                </p>
                            </div>

                            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-100">
                                <button type="button" @click="claimModalOpen = false" class="py-3.5 px-4 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition">
                                    Batal
                                </button>
                                <button type="submit" class="py-3.5 px-4 bg-gradient-to-r from-sky-500 to-sky-600 text-white font-bold rounded-xl hover:shadow-lg hover:shadow-sky-200 transition transform hover:-translate-y-0.5">
                                    Kirim Klaim
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    </div>
</x-app-layout>
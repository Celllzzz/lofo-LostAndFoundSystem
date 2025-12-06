<x-app-layout>
    {{-- State Management dengan Alpine.js: Mengatur Tipe Laporan & Preview Gambar --}}
    <div x-data="{ 
        type: 'lost', 
        imagePreview: null,
        fileChosen(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => { this.imagePreview = e.target.result; }
                reader.readAsDataURL(file);
            }
        }
    }" class="min-h-screen bg-orange-50/50 py-12 px-4 sm:px-6 lg:px-8">

        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-orange-100">
                
                <div class="px-8 py-6 transition-colors duration-500 ease-in-out"
                     :class="type === 'lost' ? 'bg-rose-100' : 'bg-emerald-100'">
                    <h2 class="text-3xl font-extrabold text-slate-800 text-center">
                        Buat Laporan <span x-text="type === 'lost' ? 'Kehilangan' : 'Penemuan'"></span>
                    </h2>
                    <p class="text-center mt-2 font-medium" 
                       :class="type === 'lost' ? 'text-rose-600' : 'text-emerald-600'">
                        <span x-show="type === 'lost'">Jangan khawatir, ayo kita cari bersama!</span>
                        <span x-show="type === 'found'">Terima kasih orang baik, mari kembalikan ke pemiliknya.</span>
                    </p>
                </div>

                <div class="p-8 sm:p-10">
                    <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf

                        <div class="flex justify-center">
                            <div class="bg-slate-100 p-1.5 rounded-full inline-flex relative shadow-inner">
                                <input type="hidden" name="type" x-model="type">
                                
                                <button type="button" @click="type = 'lost'" 
                                    class="relative px-8 py-3 rounded-full text-sm font-bold transition-all duration-300 z-10 focus:outline-none"
                                    :class="type === 'lost' ? 'text-rose-600 bg-white shadow-md' : 'text-slate-400 hover:text-slate-600'">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Barang Hilang
                                    </span>
                                </button>

                                <button type="button" @click="type = 'found'" 
                                    class="relative px-8 py-3 rounded-full text-sm font-bold transition-all duration-300 z-10 focus:outline-none"
                                    :class="type === 'found' ? 'text-emerald-600 bg-white shadow-md' : 'text-slate-400 hover:text-slate-600'">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Barang Temuan
                                    </span>
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-2">
                                <label class="block font-semibold text-sm text-slate-700 mb-2">Nama Barang</label>
                                <input type="text" name="title" 
                                    class="w-full border-slate-200 rounded-xl px-4 py-3 bg-slate-50 focus:bg-white focus:ring-2 focus:border-transparent transition" 
                                    :class="type === 'lost' ? 'focus:ring-rose-200' : 'focus:ring-emerald-200'"
                                    placeholder="Contoh: Laptop Asus Hitam" required>
                            </div>

                            <div>
                                <label class="block font-semibold text-sm text-slate-700 mb-2">Kategori</label>
                                <div class="relative">
                                    <select name="category_id" 
                                        class="w-full border-slate-200 rounded-xl px-4 py-3 bg-slate-50 focus:bg-white focus:ring-2 focus:border-transparent transition appearance-none cursor-pointer"
                                        :class="type === 'lost' ? 'focus:ring-rose-200' : 'focus:ring-emerald-200'">
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block font-semibold text-sm text-slate-700 mb-2">Tanggal Kejadian</label>
                                <input type="date" name="date" 
                                    class="w-full border-slate-200 rounded-xl px-4 py-3 bg-slate-50 focus:bg-white focus:ring-2 focus:border-transparent transition"
                                    :class="type === 'lost' ? 'focus:ring-rose-200' : 'focus:ring-emerald-200'" required>
                            </div>

                            <div class="col-span-2">
                                <label class="block font-semibold text-sm text-slate-700 mb-2">Lokasi <span x-text="type === 'lost' ? 'Hilang' : 'Ditemukan'"></span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    </div>
                                    <input type="text" name="location" 
                                        class="w-full pl-10 border-slate-200 rounded-xl px-4 py-3 bg-slate-50 focus:bg-white focus:ring-2 focus:border-transparent transition" 
                                        :class="type === 'lost' ? 'focus:ring-rose-200' : 'focus:ring-emerald-200'"
                                        placeholder="Detail lokasi, misal: Parkiran Motor Gedung C" required>
                                </div>
                            </div>

                            <div class="col-span-2">
                                <label class="block font-semibold text-sm text-slate-700 mb-2">Deskripsi Lengkap</label>
                                <textarea name="description" rows="4" 
                                    class="w-full border-slate-200 rounded-xl px-4 py-3 bg-slate-50 focus:bg-white focus:ring-2 focus:border-transparent transition" 
                                    :class="type === 'lost' ? 'focus:ring-rose-200' : 'focus:ring-emerald-200'"
                                    placeholder="Jelaskan ciri-ciri barang sedetail mungkin (warna, merk, ada stiker, lecet, dll)..." required></textarea>
                            </div>

                            <div class="col-span-2">
                                <label class="block font-semibold text-sm text-slate-700 mb-2">Foto Barang (Opsional)</label>
                                
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed rounded-xl transition-colors relative bg-slate-50 hover:bg-white cursor-pointer group"
                                     :class="type === 'lost' ? 'border-rose-200 hover:border-rose-300' : 'border-emerald-200 hover:border-emerald-300'">
                                    
                                    <input id="image-upload" name="image" type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" @change="fileChosen">
                                    
                                    <div class="space-y-1 text-center" x-show="!imagePreview">
                                        <div class="mx-auto h-12 w-12 text-slate-300 group-hover:text-slate-400 transition"
                                             :class="type === 'lost' ? 'group-hover:text-rose-400' : 'group-hover:text-emerald-400'">
                                            <svg stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <div class="flex text-sm text-slate-600 justify-center">
                                            <span class="font-medium bg-transparent rounded-md focus-within:outline-none"
                                                  :class="type === 'lost' ? 'text-rose-600' : 'text-emerald-600'">
                                                Upload Foto
                                            </span>
                                            <p class="pl-1">atau drag and drop</p>
                                        </div>
                                        <p class="text-xs text-slate-500">PNG, JPG, JPEG (Max 2MB)</p>
                                    </div>

                                    <div x-show="imagePreview" class="relative z-20" style="display: none;">
                                        <img :src="imagePreview" class="max-h-64 rounded-lg shadow-sm mx-auto object-contain">
                                        <p class="text-center text-xs text-slate-400 mt-2">Klik gambar untuk mengganti</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4">
                            <button type="submit" 
                                class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white transition duration-300 transform hover:-translate-y-1"
                                :class="type === 'lost' 
                                    ? 'bg-rose-500 hover:bg-rose-600 shadow-rose-200' 
                                    : 'bg-emerald-500 hover:bg-emerald-600 shadow-emerald-200'">
                                <span x-text="type === 'lost' ? 'Sebarkan Laporan Kehilangan' : 'Laporkan Barang Temuan'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
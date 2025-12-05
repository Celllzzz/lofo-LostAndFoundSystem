<x-app-layout>
    <div class="py-12 bg-orange-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-sm border border-orange-100 overflow-hidden md:flex">
                
                <div class="md:w-1/2 h-80 md:h-auto bg-slate-100 relative">
                    @if($item->image_path)
                        <img src="{{ asset('storage/'.$item->image_path) }}" class="w-full h-full object-cover">
                    @else
                        <div class="flex items-center justify-center h-full text-slate-300">No Image</div>
                    @endif
                    <span class="absolute top-4 left-4 px-4 py-1 rounded-full text-sm font-bold uppercase tracking-wider {{ $item->type == 'lost' ? 'bg-rose-500 text-white' : 'bg-emerald-500 text-white' }}">
                        {{ $item->type == 'lost' ? 'Hilang' : 'Ditemukan' }}
                    </span>
                </div>

                <div class="p-8 md:w-1/2 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center space-x-2 mb-2">
                             <span class="px-3 py-1 bg-slate-100 text-slate-600 text-xs rounded-full font-bold">{{ $item->category->name }}</span>
                             <span class="text-slate-400 text-xs">{{ $item->date->format('l, d M Y') }}</span>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-800 mb-4">{{ $item->title }}</h2>
                        
                        <div class="space-y-3 text-slate-600 text-sm">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 mr-2 text-sky-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                <span>{{ $item->location }}</span>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-5 h-5 mr-2 text-sky-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                                <p>{{ $item->description }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 border-t border-slate-100 pt-6">
                        @if($item->type == 'found' && $item->status == 'open' && $item->user_id != auth()->id())
                            <div x-data="{ open: false }">
                                <button @click="open = true" class="w-full py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-xl shadow-lg shadow-emerald-200 transition">
                                    Ini Barang Saya! (Klaim)
                                </button>

                                <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 px-4" style="display: none;">
                                    <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl" @click.away="open = false">
                                        <h3 class="text-xl font-bold text-slate-700 mb-4">Ajukan Klaim</h3>
                                        <form action="{{ route('claims.store', $item) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-slate-600 mb-1">Bukti Kepemilikan (Deskripsi)</label>
                                                <textarea name="proof_description" class="w-full border-slate-200 rounded-lg focus:ring-emerald-200 text-sm" rows="3" placeholder="Sebutkan isi tas, tanda lahir, stiker, dll..." required></textarea>
                                            </div>
                                            <div class="mb-6">
                                                <label class="block text-sm font-medium text-slate-600 mb-1">Upload Bukti (Struk/Foto Lama)</label>
                                                <input type="file" name="proof_file" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                                            </div>
                                            <div class="flex justify-end space-x-2">
                                                <button type="button" @click="open = false" class="px-4 py-2 text-slate-500 hover:text-slate-700 font-medium">Batal</button>
                                                <button type="submit" class="px-4 py-2 bg-emerald-500 text-white rounded-lg font-bold hover:bg-emerald-600">Kirim Klaim</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @elseif($item->status == 'claimed')
                            <button disabled class="w-full py-3 bg-slate-200 text-slate-400 font-bold rounded-xl cursor-not-allowed">
                                Barang Sedang Dalam Proses Klaim
                            </button>
                        @elseif($item->user_id == auth()->id())
                             <div class="text-center text-sm text-slate-400">Anda adalah pelapor barang ini.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
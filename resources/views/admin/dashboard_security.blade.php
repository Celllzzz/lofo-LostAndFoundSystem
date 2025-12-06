<x-app-layout>
    <div class="min-h-screen bg-slate-50 font-sans" x-data="{ activeTab: 'items_found' }">
        
        <div class="bg-white border-b border-orange-200 shadow-sm sticky top-0 z-30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-black text-slate-800">Security Command Center</h1>
                        <p class="text-xs text-slate-500">Halo, <span class="font-bold text-sky-600">{{ Auth::user()->name }}</span>.</p>
                    </div>
                    <div class="mt-4 md:mt-0 flex gap-2 overflow-x-auto">
                        <button @click="activeTab = 'items_found'" :class="activeTab === 'items_found' ? 'bg-sky-500 text-white shadow-lg shadow-sky-200' : 'bg-white text-slate-500 border border-slate-200'" class="px-4 py-2 rounded-xl font-bold text-xs transition whitespace-nowrap flex items-center">
                            Verifikasi Fisik
                            @if($unverifiedFoundItems->count() > 0) <span class="ml-2 bg-white text-sky-600 px-1.5 py-0.5 rounded text-[10px]">{{ $unverifiedFoundItems->count() }}</span> @endif
                        </button>
                        <button @click="activeTab = 'claims'" :class="activeTab === 'claims' ? 'bg-yellow-400 text-white shadow-lg shadow-yellow-200' : 'bg-white text-slate-500 border border-slate-200'" class="px-4 py-2 rounded-xl font-bold text-xs transition whitespace-nowrap flex items-center">
                            Klaim Masuk
                            @if($pendingClaims->count() > 0) <span class="ml-2 bg-white text-yellow-500 px-1.5 py-0.5 rounded text-[10px]">{{ $pendingClaims->count() }}</span> @endif
                        </button>
                        <button @click="activeTab = 'handover'" :class="activeTab === 'handover' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-200' : 'bg-white text-slate-500 border border-slate-200'" class="px-4 py-2 rounded-xl font-bold text-xs transition whitespace-nowrap flex items-center">
                            Serah Terima
                            @if($approvedClaims->count() > 0) <span class="ml-2 bg-white text-emerald-600 px-1.5 py-0.5 rounded text-[10px]">{{ $approvedClaims->count() }}</span> @endif
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <div x-show="activeTab === 'items_found'" class="animate-fade-in">
                @if($unverifiedFoundItems->isEmpty())
                    <div class="flex flex-col items-center justify-center py-20 bg-white rounded-[2rem] border-2 border-dashed border-slate-200 text-center">
                        <div class="bg-slate-50 p-4 rounded-full mb-3"><svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
                        <p class="text-slate-400 font-bold">Semua barang temuan sudah diverifikasi.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($unverifiedFoundItems as $item)
                        <div x-data="{ modalOpen: false }" class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition">
                            <div @click="modalOpen = true" class="cursor-pointer">
                                <div class="flex items-start gap-4 mb-4">
                                    <img src="{{ $item->image_path ? asset('storage/'.$item->image_path) : 'https://via.placeholder.com/100' }}" class="w-16 h-16 rounded-2xl object-cover bg-slate-100">
                                    <div>
                                        <h4 class="font-bold text-slate-800 line-clamp-1">{{ $item->title }}</h4>
                                        <p class="text-xs text-slate-500 mb-1">{{ $item->location }}</p>
                                        <span class="bg-orange-100 text-orange-600 text-[10px] font-bold px-2 py-0.5 rounded">Cek Fisik</span>
                                    </div>
                                </div>
                                <p class="text-xs text-slate-600 bg-slate-50 p-3 rounded-xl mb-4 line-clamp-2">"{{ $item->description }}"</p>
                            </div>
                            
                            <form action="{{ route('admin.items.verify', $item) }}" method="POST">
                                @csrf @method('PATCH')
                                <button onclick="confirmSubmit(event, 'Fisik barang ada di pos?')" class="w-full py-3 bg-sky-500 text-white font-bold rounded-xl text-sm hover:bg-sky-600 shadow-lg shadow-sky-200 transition">Terima Barang</button>
                            </form>

                            <template x-teleport="body">
                                <div x-show="modalOpen" class="fixed inset-0 z-[99] flex items-center justify-center bg-black/60 backdrop-blur-sm" style="display: none;">
                                    <div class="bg-white rounded-3xl w-full max-w-lg overflow-hidden shadow-2xl relative" @click.away="modalOpen = false">
                                        <div class="relative h-56 bg-slate-800">
                                            @if($item->image_path)
                                                <img src="{{ asset('storage/'.$item->image_path) }}" class="w-full h-full object-cover opacity-80">
                                            @endif
                                            <button @click="modalOpen = false" class="absolute top-4 right-4 bg-black/40 text-white p-2 rounded-full hover:bg-black/60 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                        </div>
                                        <div class="p-6">
                                            <h3 class="text-xl font-black text-slate-800">{{ $item->title }}</h3>
                                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 text-sm text-slate-700 mt-4 mb-4">
                                                {{ $item->description }}
                                            </div>
                                            <div class="text-xs text-slate-500 grid grid-cols-2 gap-2">
                                                <p>Lokasi: <b>{{ $item->location }}</b></p>
                                                <p>Pelapor: <b>{{ $item->user->name }}</b></p>
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
                    <div class="flex flex-col items-center justify-center py-20 bg-white rounded-[2rem] border-2 border-dashed border-slate-200 text-center">
                        <div class="bg-yellow-50 p-4 rounded-full mb-3"><svg class="w-10 h-10 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                        <p class="text-slate-400 font-bold">Tidak ada klaim pending.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($pendingClaims as $claim)
                        <div x-data="{ modalOpen: false }" class="bg-white p-6 rounded-3xl border border-yellow-100 shadow-sm flex flex-col md:flex-row gap-6 hover:shadow-md transition">
                            <div class="flex-1 flex gap-4 cursor-pointer" @click="modalOpen = true">
                                <img src="{{ $claim->item->image_path ? asset('storage/'.$claim->item->image_path) : 'https://via.placeholder.com/100' }}" class="w-20 h-20 rounded-2xl object-cover bg-slate-100">
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">Barang</span>
                                    <h4 class="font-bold text-slate-800 text-lg leading-tight">{{ $claim->item->title }}</h4>
                                    <p class="text-xs text-slate-500 mt-1">Klaim oleh: <span class="font-bold text-slate-700">{{ $claim->user->name }}</span></p>
                                    <p class="text-xs text-sky-500 font-bold mt-2">Klik untuk detail</p>
                                </div>
                            </div>
                            
                            <div class="flex-1 bg-yellow-50/50 p-4 rounded-2xl border border-yellow-100">
                                <span class="text-[10px] font-bold text-yellow-600 uppercase">Bukti Kepemilikan</span>
                                <p class="text-sm text-slate-700 italic mt-1 line-clamp-2">"{{ $claim->proof_description }}"</p>
                            </div>
                            
                            <div class="flex flex-col gap-2 justify-center w-full md:w-48">
                                <form action="{{ route('admin.claims.update', $claim) }}" method="POST"><@csrf @method('PATCH')<button name="status" value="verified" onclick="confirmSubmit(event, 'Terima Klaim?')" class="w-full py-2 bg-emerald-500 text-white font-bold rounded-xl text-sm shadow-md hover:bg-emerald-600">Terima (Valid)</button></form>
                                <form action="{{ route('admin.claims.update', $claim) }}" method="POST"><@csrf @method('PATCH')<button name="status" value="rejected" onclick="confirmSubmit(event, 'Tolak Klaim?')" class="w-full py-2 bg-white text-rose-500 border border-rose-200 font-bold rounded-xl text-sm hover:bg-rose-50">Tolak (Invalid)</button></form>
                            </div>

                            <template x-teleport="body">
                                <div x-show="modalOpen" class="fixed inset-0 z-[99] flex items-center justify-center bg-black/60 backdrop-blur-sm" style="display: none;">
                                    <div class="bg-white rounded-3xl w-full max-w-lg overflow-hidden shadow-2xl relative" @click.away="modalOpen = false">
                                        <div class="p-6">
                                            <div class="flex justify-between items-start mb-4">
                                                <h3 class="text-xl font-black text-slate-800">Detail Klaim</h3>
                                                <button @click="modalOpen = false" class="text-slate-400"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                            </div>
                                            
                                            <div class="bg-slate-50 p-4 rounded-xl mb-4">
                                                <p class="text-xs font-bold text-slate-400 uppercase">Barang</p>
                                                <p class="font-bold text-slate-800">{{ $claim->item->title }}</p>
                                            </div>

                                            <div class="bg-yellow-50 p-4 rounded-xl border border-yellow-100">
                                                <p class="text-xs font-bold text-yellow-600 uppercase">Bukti Deskripsi</p>
                                                <p class="text-sm text-slate-700 italic mt-1">{{ $claim->proof_description }}</p>
                                                
                                                @if($claim->proof_file)
                                                    <div class="mt-4 pt-4 border-t border-yellow-200">
                                                        <p class="text-xs font-bold text-yellow-600 uppercase mb-2">Bukti Foto</p>
                                                        <img src="{{ asset('storage/'.$claim->proof_file) }}" class="w-full rounded-lg border border-slate-200">
                                                    </div>
                                                @endif
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
                    <div class="flex flex-col items-center justify-center py-20 bg-white rounded-[2rem] border-2 border-dashed border-slate-200 text-center">
                        <div class="bg-emerald-50 p-4 rounded-full mb-3"><svg class="w-10 h-10 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                        <p class="text-slate-400 font-bold">Belum ada barang siap diambil.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($approvedClaims as $claim)
                        <div class="bg-white p-6 rounded-3xl border border-emerald-100 shadow-sm relative overflow-hidden">
                            <div class="absolute top-0 right-0 bg-emerald-500 text-white text-[10px] font-bold px-3 py-1 rounded-bl-xl">SIAP DIAMBIL</div>
                            <div class="flex items-center gap-4 mb-6">
                                <img src="{{ $claim->item->image_path ? asset('storage/'.$claim->item->image_path) : 'https://via.placeholder.com/100' }}" class="w-20 h-20 rounded-2xl object-cover bg-slate-100">
                                <div>
                                    <h4 class="font-bold text-slate-800 text-lg">{{ $claim->item->title }}</h4>
                                    <p class="text-sm text-slate-500">Pemilik: <span class="font-bold text-emerald-600">{{ $claim->user->name }}</span></p>
                                </div>
                            </div>
                            <form action="{{ route('admin.claims.update', $claim) }}" method="POST">
                                @csrf @method('PATCH')
                                <button name="status" value="completed" onclick="confirmSubmit(event, 'Barang sudah diserahkan?')" class="w-full py-3 bg-slate-900 text-white font-bold rounded-xl text-sm hover:bg-slate-800 shadow-lg transition flex justify-center items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Konfirmasi Serah Terima
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>

    <style>.animate-fade-in { animation: fadeIn 0.4s ease-out; } @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }</style>
</x-app-layout>
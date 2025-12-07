<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'LoFo') }} - Sistem Kehilangan & Penemuan Barang</title>
    
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        
        /* Halus & Modern Animation */
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-10px); } }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        
        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-fade-up { animation: fadeUp 0.8s ease-out forwards; }
        
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        
        .scrollbar-hide::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-900 selection:bg-orange-200 selection:text-orange-900" x-data="{ scrolled: false, mobileMenu: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">

    <nav :class="scrolled ? 'bg-white/90 backdrop-blur-md border-b border-orange-100 shadow-sm py-3' : 'bg-transparent py-6'" class="fixed w-full z-50 transition-all duration-300 top-0 left-0">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <a href="#" class="flex items-center group z-50">
                    <div class="bg-orange-50 p-2 rounded-full border border-orange-100 group-hover:bg-orange-100 transition duration-300">
                        <svg class="w-6 h-6 text-sky-500 group-hover:scale-110 transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v.01M10 13a3 3 0 003-3"></path>
                        </svg>
                    </div>
                    <span class="ml-2 text-xl font-black text-slate-700 tracking-tight group-hover:text-slate-900 transition">Lo<span class="text-sky-500">Fo</span>.</span>
                </a>

                <div class="hidden md:flex items-center gap-8">
                    <a href="#stats" class="text-sm font-bold text-slate-500 hover:text-orange-500 transition">Statistik</a>
                    <a href="#how-it-works" class="text-sm font-bold text-slate-500 hover:text-orange-500 transition">Cara Kerja</a>
                    <a href="#latest" class="text-sm font-bold text-slate-500 hover:text-orange-500 transition">Terbaru</a>
                </div>

                <div class="hidden md:flex items-center gap-3">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 bg-slate-800 text-white text-sm font-bold rounded-xl shadow-lg hover:bg-slate-700 hover:-translate-y-0.5 transition duration-300">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-bold text-slate-600 hover:text-slate-900 px-4 py-2 transition">Masuk</a>
                            <a href="{{ route('register') }}" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 text-sm font-bold rounded-xl shadow-sm hover:bg-orange-50 hover:border-orange-200 hover:text-orange-600 transition duration-300">Daftar</a>
                        @endauth
                    @endif
                </div>

                <button @click="mobileMenu = !mobileMenu" class="md:hidden z-50 p-2 text-slate-600 hover:bg-slate-100 rounded-xl transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        <path x-show="mobileMenu" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div x-show="mobileMenu" x-transition.opacity class="fixed inset-0 bg-white/95 backdrop-blur-xl z-40 flex flex-col justify-center items-center space-y-8 md:hidden">
            <a href="#stats" @click="mobileMenu = false" class="text-xl font-bold text-slate-800">Statistik</a>
            <a href="#how-it-works" @click="mobileMenu = false" class="text-xl font-bold text-slate-800">Cara Kerja</a>
            <a href="#latest" @click="mobileMenu = false" class="text-xl font-bold text-slate-800">Laporan Terbaru</a>
            <div class="w-16 h-1 bg-orange-100 rounded-full"></div>
            @auth
                <a href="{{ url('/dashboard') }}" class="px-8 py-3 bg-slate-900 text-white font-bold rounded-xl">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="text-lg font-bold text-slate-600">Masuk</a>
                <a href="{{ route('register') }}" class="px-8 py-3 bg-orange-500 text-white font-bold rounded-xl shadow-lg shadow-orange-200">Daftar Akun</a>
            @endauth
        </div>
    </nav>

    <section class="relative pt-32 pb-20 lg:pt-44 lg:pb-32 overflow-hidden bg-slate-50">
        <div class="absolute top-0 right-0 -mr-40 -mt-40 w-[600px] h-[600px] bg-orange-50 rounded-full blur-3xl opacity-60"></div>
        <div class="absolute bottom-0 left-0 -ml-40 -mb-40 w-[600px] h-[600px] bg-sky-50 rounded-full blur-3xl opacity-60"></div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <div class="lg:w-1/2 text-center lg:text-left">
                    <div class="inline-flex items-center px-3 py-1 rounded-full bg-white border border-slate-200 shadow-sm text-xs font-bold text-slate-500 mb-6 animate-fade-up">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 mr-2 animate-pulse"></span>
                        Portal Resmi Kampus
                    </div>
                    
                    <h1 class="text-4xl lg:text-6xl font-black text-slate-800 tracking-tight leading-[1.15] mb-6 animate-fade-up delay-100">
                        Barang Tertinggal?<br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-rose-500">Temukan di Sini.</span>
                    </h1>
                    
                    <p class="text-lg text-slate-500 mb-8 leading-relaxed max-w-lg mx-auto lg:mx-0 animate-fade-up delay-200">
                        Platform terpusat untuk melaporkan kehilangan dan penemuan barang di lingkungan kampus. Aman, transparan, dan mudah digunakan.
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 animate-fade-up delay-300">
                        <a href="{{ route('items.create', ['type' => 'lost']) }}" class="w-full sm:w-auto px-8 py-3.5 bg-slate-800 text-white font-bold rounded-2xl shadow-lg hover:bg-slate-700 hover:-translate-y-1 transition duration-300 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            Lapor Hilang
                        </a>
                        <a href="{{ route('items.create', ['type' => 'found']) }}" class="w-full sm:w-auto px-8 py-3.5 bg-white text-slate-700 border border-slate-200 font-bold rounded-2xl shadow-sm hover:border-emerald-300 hover:text-emerald-600 hover:bg-emerald-50 transition duration-300 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Lapor Temuan
                        </a>
                    </div>
                </div>

                <div class="lg:w-1/2 relative animate-fade-up delay-300">
                    <div class="relative bg-white p-3 rounded-[2.5rem] shadow-2xl border border-slate-100 transform rotate-1 hover:rotate-0 transition duration-700">
                        <img src="https://images.unsplash.com/photo-1497633762265-9d179a990aa6?q=80&w=2073&auto=format&fit=crop" class="rounded-[2rem] w-full object-cover h-[350px] lg:h-[450px]" alt="Ruang Belajar Kampus">
                        
                        <div class="absolute -left-6 top-10 bg-white p-3 pr-5 rounded-xl shadow-lg border border-slate-100 animate-float flex gap-3 items-center">
                            <div class="w-10 h-10 rounded-lg bg-rose-50 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-rose-500 uppercase">Hilang</p>
                                <p class="text-xs font-black text-slate-800">Laptop Asus ROG</p>
                            </div>
                        </div>

                        <div class="absolute -right-6 bottom-16 bg-white p-3 pr-5 rounded-xl shadow-lg border border-slate-100 animate-float flex gap-3 items-center" style="animation-delay: 2s;">
                            <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-emerald-500 uppercase">Ditemukan</p>
                                <p class="text-xs font-black text-slate-800">Kamera Canon</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="stats" class="py-12 bg-white border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center p-6 bg-slate-50/50 rounded-2xl border border-slate-100">
                    <p class="text-4xl font-black text-slate-800 mb-1">{{ $stats['lost'] ?? 0 }}</p>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Laporan Hilang</p>
                </div>
                <div class="text-center p-6 bg-slate-50/50 rounded-2xl border border-slate-100">
                    <p class="text-4xl font-black text-slate-800 mb-1">{{ $stats['found'] ?? 0 }}</p>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Barang Ditemukan</p>
                </div>
                <div class="text-center p-6 bg-slate-50/50 rounded-2xl border border-slate-100">
                    <p class="text-4xl font-black text-emerald-500 mb-1">{{ $stats['returned'] ?? 0 }}</p>
                    <p class="text-xs font-bold text-emerald-600 uppercase tracking-widest">Berhasil Kembali</p>
                </div>
                <div class="flex items-center justify-center p-6 bg-orange-50/50 rounded-2xl border border-orange-100 text-center">
                    <div>
                        <p class="text-sm font-bold text-slate-700">Komunitas Kampus</p>
                        <p class="text-xs text-slate-500">Saling Peduli & Menjaga</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="how-it-works" class="py-24 bg-slate-50/50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl font-black text-slate-800 mb-4">Bagaimana Cara Kerjanya?</h2>
                <p class="text-slate-500 text-lg">Proses pelaporan yang simpel dan transparan.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm flex flex-col items-center text-center hover:-translate-y-1 transition duration-300">
                    <div class="w-16 h-16 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-500 mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <h3 class="text-lg font-black text-slate-800 mb-2">1. Buat Laporan</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Laporkan detail barang yang hilang atau ditemukan lengkap dengan foto dan lokasi.</p>
                </div>

                <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm flex flex-col items-center text-center hover:-translate-y-1 transition duration-300">
                    <div class="w-16 h-16 bg-sky-50 rounded-2xl flex items-center justify-center text-sky-500 mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-black text-slate-800 mb-2">2. Verifikasi & Cari</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Sistem memverifikasi laporan. Gunakan fitur pencarian untuk mencocokkan barang.</p>
                </div>

                <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm flex flex-col items-center text-center hover:-translate-y-1 transition duration-300">
                    <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500 mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-black text-slate-800 mb-2">3. Klaim & Ambil</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Ajukan klaim kepemilikan. Jika valid, barang dapat diambil di pos keamanan.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="latest" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-end mb-10 gap-4">
                <div>
                    <h2 class="text-3xl font-black text-slate-900 mb-2">Laporan Terbaru</h2>
                    <p class="text-slate-500 text-sm">Barang yang baru saja dilaporkan.</p>
                </div>
                <a href="{{ route('items.index') }}" class="text-xs font-bold bg-white border border-slate-200 px-5 py-2.5 rounded-xl text-slate-600 hover:text-sky-600 hover:border-sky-300 transition flex items-center shadow-sm">
                    Lihat Semua <svg class="w-3 h-3 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($latestItems ?? [] as $item)
                <a href="{{ route('items.index') }}" class="group bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-slate-100 hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col h-full">
                    <div class="relative h-56 bg-slate-50 overflow-hidden">
                        @if($item->image_path)
                            <img src="{{ asset('storage/'.$item->image_path) }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-105">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-300">
                                <svg class="w-12 h-12 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="text-[10px] font-bold uppercase">No Image</span>
                            </div>
                        @endif
                        
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest shadow-sm text-white {{ $item->type == 'lost' ? 'bg-rose-500' : 'bg-emerald-500' }}">
                                {{ $item->type == 'lost' ? 'Hilang' : 'Temuan' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-[10px] font-bold text-sky-600 bg-sky-50 px-2 py-1 rounded-lg border border-sky-100 uppercase tracking-wide">{{ $item->category->name ?? 'Umum' }}</span>
                            <span class="text-[10px] font-bold text-slate-400 bg-slate-50 px-2 py-1 rounded-lg">{{ $item->created_at->diffForHumans() }}</span>
                        </div>
                        <h3 class="font-bold text-lg text-slate-800 mb-2 line-clamp-1 group-hover:text-sky-600 transition duration-300">{{ $item->title }}</h3>
                        <p class="text-sm text-slate-500 line-clamp-2 mb-4 leading-relaxed">{{ $item->description }}</p>
                        
                        <div class="mt-auto pt-4 border-t border-slate-50 flex items-center text-xs font-bold text-slate-500">
                            <svg class="w-3 h-3 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            {{ Str::limit($item->location, 25) }}
                        </div>
                    </div>
                </a>
                @empty
                <div class="col-span-full py-16 text-center border-2 border-dashed border-slate-200 rounded-3xl bg-slate-50">
                    <p class="text-slate-400 font-bold text-sm">Belum ada laporan terbaru saat ini.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="py-20 bg-slate-900 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-orange-500 rounded-full blur-3xl opacity-10 translate-x-1/3 -translate-y-1/3"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-sky-500 rounded-full blur-3xl opacity-10 -translate-x-1/3 translate-y-1/3"></div>
        
        <div class="max-w-4xl mx-auto px-6 text-center relative z-10">
            <h2 class="text-3xl lg:text-5xl font-black text-white mb-6">Jangan Biarkan Barang Hilang.</h2>
            <p class="text-slate-400 text-lg mb-10 max-w-xl mx-auto">Setiap laporan Anda membantu mengembalikan barang ke pemiliknya.</p>
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                @auth
                    <a href="{{ route('items.index') }}" class="px-8 py-3.5 bg-white text-slate-900 font-bold rounded-2xl shadow-lg hover:bg-slate-100 transition duration-300">
                        Lihat Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="px-8 py-3.5 bg-gradient-to-r from-orange-500 to-rose-500 text-white font-bold rounded-2xl shadow-lg hover:shadow-orange-500/25 transition duration-300">
                        Buat Akun Sekarang
                    </a>
                    <a href="{{ route('login') }}" class="px-8 py-3.5 bg-transparent border border-slate-700 text-white font-bold rounded-2xl hover:bg-slate-800 transition duration-300">
                        Masuk
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <footer class="bg-white py-12 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-3">
                <div class="bg-orange-50 p-2 rounded-xl border border-orange-100">
                    <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v.01M10 13a3 3 0 003-3"></path>
                    </svg>
                </div>
                <span class="text-lg font-black text-slate-800 tracking-tight">Lo<span class="text-sky-500">Fo</span>.</span>
            </div>
            
            <p class="text-xs font-bold text-slate-400">&copy; {{ date('Y') }} Lost & Found System. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
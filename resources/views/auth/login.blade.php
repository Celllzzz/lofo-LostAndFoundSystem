<x-guest-layout>
    <x-slot name="title">Masuk</x-slot>

    <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white p-8 sm:p-10 relative overflow-hidden">
        
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <x-auth-logo />
            </div>
            <h2 class="text-xl font-bold text-slate-800">Selamat Datang Kembali</h2>
            <p class="text-xs text-slate-500 mt-1">Masuk untuk mengelola laporan barang.</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Email Kampus</label>
                <div class="relative">
                    <input id="email" name="email" type="email" autocomplete="email" required autofocus
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-sky-400 focus:ring-4 focus:ring-sky-50 transition-all outline-none text-sm font-medium text-slate-700 placeholder-slate-400" 
                        placeholder="nama@mahasiswa.ac.id" :value="old('email')">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kata Sandi</label>
                <div class="relative">
                    <input id="password" name="password" type="password" autocomplete="current-password" required 
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-sky-400 focus:ring-4 focus:ring-sky-50 transition-all outline-none text-sm font-medium text-slate-700 placeholder-slate-400" 
                        placeholder="••••••••">
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center cursor-pointer group">
                    <input id="remember_me" name="remember" type="checkbox" class="w-4 h-4 text-sky-500 border-slate-300 rounded focus:ring-sky-400 cursor-pointer">
                    <span class="ml-2 text-xs font-bold text-slate-500 group-hover:text-slate-700 transition">Ingat saya</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs font-bold text-orange-500 hover:text-orange-600 transition">
                        Lupa password?
                    </a>
                @endif
            </div>

            <button type="submit" class="w-full py-3.5 px-4 bg-slate-900 text-white font-bold rounded-xl shadow-lg hover:bg-slate-800 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 text-sm">
                Masuk ke Akun
            </button>
        </form>

        <div class="mt-8 text-center border-t border-slate-100 pt-6">
            <p class="text-sm text-slate-500">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="font-bold text-sky-600 hover:text-sky-700 transition">Daftar sekarang</a>
            </p>
        </div>
    </div>
</x-guest-layout>
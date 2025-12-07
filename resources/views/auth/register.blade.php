<x-guest-layout>
    <x-slot name="title">Daftar</x-slot>

    <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white p-8 sm:p-10 relative overflow-hidden">
        
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <x-auth-logo />
            </div>
            <h2 class="text-xl font-bold text-slate-800">Buat Akun Baru</h2>
            <p class="text-xs text-slate-500 mt-1">Bergabung dengan komunitas LoFo.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                <input id="name" name="name" type="text" autocomplete="name" required autofocus
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-orange-400 focus:ring-4 focus:ring-orange-50 transition-all outline-none text-sm font-medium text-slate-700 placeholder-slate-400" 
                    placeholder="John Doe" :value="old('name')">
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <label for="email" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Email Kampus</label>
                <input id="email" name="email" type="email" autocomplete="email" required 
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-orange-400 focus:ring-4 focus:ring-orange-50 transition-all outline-none text-sm font-medium text-slate-700 placeholder-slate-400" 
                    placeholder="nama@mahasiswa.ac.id" :value="old('email')">
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <input type="hidden" name="role" value="mahasiswa"> 

            <div>
                <label for="password" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Kata Sandi</label>
                <input id="password" name="password" type="password" autocomplete="new-password" required 
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-orange-400 focus:ring-4 focus:ring-orange-50 transition-all outline-none text-sm font-medium text-slate-700 placeholder-slate-400" 
                    placeholder="Minimal 8 karakter">
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Konfirmasi Sandi</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required 
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-orange-400 focus:ring-4 focus:ring-orange-50 transition-all outline-none text-sm font-medium text-slate-700 placeholder-slate-400" 
                    placeholder="Ulangi kata sandi">
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-orange-500 to-rose-500 text-white font-bold rounded-xl shadow-lg shadow-orange-200 hover:shadow-orange-300 hover:scale-[1.02] transition-all duration-300 text-sm mt-2">
                Daftar Sekarang
            </button>
        </form>

        <div class="mt-6 text-center border-t border-slate-100 pt-6">
            <p class="text-sm text-slate-500">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="font-bold text-sky-600 hover:text-sky-700 transition">Masuk di sini</a>
            </p>
        </div>
    </div>
</x-guest-layout>
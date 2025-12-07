<x-guest-layout>
    <x-slot name="title">Lupa Kata Sandi</x-slot>

    <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white p-8 sm:p-10 relative overflow-hidden">
        
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <x-auth-logo />
            </div>
            <h2 class="text-xl font-bold text-slate-800">Lupa Kata Sandi?</h2>
        </div>

        <div class="mb-6 text-sm text-slate-500 text-center bg-slate-50 p-4 rounded-xl border border-slate-100">
            {{ __('Jangan khawatir. Masukkan email Anda dan kami akan mengirimkan tautan untuk mereset kata sandi Anda.') }}
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Email Kampus</label>
                <div class="relative">
                    <input id="email" name="email" type="email" required autofocus
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-sky-400 focus:ring-4 focus:ring-sky-50 transition-all outline-none text-sm font-medium text-slate-700 placeholder-slate-400" 
                        placeholder="nama@mahasiswa.ac.id" :value="old('email')">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('login') }}" class="text-xs font-bold text-slate-400 hover:text-slate-600 transition">Kembali</a>
                <button type="submit" class="py-3 px-6 bg-slate-900 text-white font-bold rounded-xl shadow-lg hover:bg-slate-800 hover:-translate-y-0.5 transition-all duration-300 text-xs">
                    {{ __('Kirim Link Reset') }}
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
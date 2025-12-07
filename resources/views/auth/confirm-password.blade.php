<x-guest-layout>
    <x-slot name="title">Konfirmasi Akses</x-slot>

    <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white p-8 sm:p-10 relative overflow-hidden">
        
        <div class="text-center mb-6">
            <div class="flex justify-center mb-4">
                <x-auth-logo />
            </div>
            <h2 class="text-lg font-black text-slate-800">Konfirmasi Akses</h2>
            <p class="text-xs text-slate-500 mt-2">{{ __('Ini adalah area aman. Mohon konfirmasi kata sandi Anda sebelum melanjutkan.') }}</p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
            @csrf

            <div>
                <label for="password" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kata Sandi</label>
                <input id="password" name="password" type="password" required autocomplete="current-password"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-sky-400 focus:ring-4 focus:ring-sky-50 transition-all outline-none text-sm font-medium text-slate-700 placeholder-slate-400">
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <button type="submit" class="w-full py-3.5 px-4 bg-slate-900 text-white font-bold rounded-xl shadow-lg hover:bg-slate-800 transition-all duration-300 text-sm">
                {{ __('Konfirmasi') }}
            </button>
        </form>
    </div>
</x-guest-layout>
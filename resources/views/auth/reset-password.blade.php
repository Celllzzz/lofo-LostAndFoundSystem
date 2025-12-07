<x-guest-layout>
    <x-slot name="title">Reset Kata Sandi</x-slot>

    <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white p-8 sm:p-10 relative overflow-hidden">
        
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <x-auth-logo />
            </div>
            <h2 class="text-xl font-bold text-slate-800">Buat Kata Sandi Baru</h2>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div>
                <label for="email" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Email</label>
                <input id="email" name="email" type="email" required autofocus
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-sky-400 focus:ring-4 focus:ring-sky-50 transition-all outline-none text-sm font-medium text-slate-700" 
                    value="{{ old('email', $request->email) }}">
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kata Sandi Baru</label>
                <input id="password" name="password" type="password" required autocomplete="new-password"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-sky-400 focus:ring-4 focus:ring-sky-50 transition-all outline-none text-sm font-medium text-slate-700">
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Konfirmasi Kata Sandi</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-sky-400 focus:ring-4 focus:ring-sky-50 transition-all outline-none text-sm font-medium text-slate-700">
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <button type="submit" class="w-full py-3.5 px-4 bg-slate-900 text-white font-bold rounded-xl shadow-lg hover:bg-slate-800 transition-all duration-300 text-sm">
                {{ __('Reset Password') }}
            </button>
        </form>
    </div>
</x-guest-layout>
<x-guest-layout>
    <x-slot name="title">Verifikasi Email</x-slot>

    <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white p-8 sm:p-10 relative overflow-hidden text-center">
        
        <div class="flex justify-center mb-6">
            <x-auth-logo />
        </div>

        <h2 class="text-xl font-black text-slate-800 mb-4">Verifikasi Email Anda</h2>

        <div class="mb-6 text-sm text-slate-500 leading-relaxed">
            {{ __('Terima kasih telah mendaftar! Sebelum memulai, mohon verifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan ke email Anda.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-6 font-medium text-sm text-emerald-600 bg-emerald-50 p-3 rounded-xl">
                {{ __('Tautan verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.') }}
            </div>
        @endif

        <div class="flex flex-col gap-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full py-3.5 px-4 bg-slate-900 text-white font-bold rounded-xl shadow-lg hover:bg-slate-800 transition-all duration-300 text-sm">
                    {{ __('Kirim Ulang Email Verifikasi') }}
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-xs font-bold text-slate-400 hover:text-slate-600 underline">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Auth' }} - {{ config('app.name', 'LoFo') }}</title>

        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Animasi Background Blob Ringan */
            @keyframes blob { 
                0% { transform: translate(0px, 0px) scale(1); } 
                33% { transform: translate(30px, -50px) scale(1.1); } 
                66% { transform: translate(-20px, 20px) scale(0.9); } 
                100% { transform: translate(0px, 0px) scale(1); } 
            }
            .animate-blob { animation: blob 7s infinite; }
            .animation-delay-2000 { animation-delay: 2s; }
            .animation-delay-4000 { animation-delay: 4s; }
            
            @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
            .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; }
        </style>
    </head>
    <body class="font-sans text-slate-900 antialiased bg-slate-50 relative min-h-screen flex items-center justify-center overflow-x-hidden selection:bg-orange-500 selection:text-white">
        
        <div class="fixed inset-0 w-full h-full -z-10 pointer-events-none overflow-hidden">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-orange-200/30 rounded-full mix-blend-multiply filter blur-[64px] opacity-70 animate-blob"></div>
            <div class="absolute top-0 right-1/4 w-96 h-96 bg-sky-200/30 rounded-full mix-blend-multiply filter blur-[64px] opacity-70 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-32 left-1/3 w-96 h-96 bg-emerald-200/30 rounded-full mix-blend-multiply filter blur-[64px] opacity-70 animate-blob animation-delay-4000"></div>
        </div>

        <div class="w-full max-w-md px-6 py-8 animate-fade-in">
            {{ $slot }}
            
            <div class="mt-8 text-center">
                <p class="text-xs font-bold text-slate-400">&copy; {{ date('Y') }} LoFo System. Aman & Terpercaya.</p>
            </div>
        </div>
        
    </body>
</html>
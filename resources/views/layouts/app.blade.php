<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LoFo') }}</title>
        
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,900&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-slate-50">
            @include('layouts.navigation')

            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                {{ $slot }}
            </main>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                
                // 1. Cek Flash Message Session (Success)
                @if(session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: "{{ session('success') }}",
                        confirmButtonColor: '#0ea5e9', // Sky-500
                        timer: 3000,
                        timerProgressBar: true
                    });
                @endif

                // 2. Cek Flash Message Session (Error)
                @if(session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "{{ session('error') }}",
                        confirmButtonColor: '#f43f5e' // Rose-500
                    });
                @endif

                // 3. Cek Validation Errors
                @if($errors->any())
                    Swal.fire({
                        icon: 'warning',
                        title: 'Periksa Kembali Form',
                        html: '<ul class="text-left text-sm">@foreach($errors->all() as $error)<li>• {{ $error }}</li>@endforeach</ul>',
                        confirmButtonColor: '#f43f5e'
                    });
                @endif
            });

            // --- GLOBAL FUNCTIONS ---

            /**
             * Universal Confirmation Dialog (UPDATED)
             * Fix: Sekarang otomatis menangkap name & value dari tombol submit
             */
            window.confirmSubmit = function(event, questionText = 'Apakah Anda yakin?', confirmText = 'Ya, Lanjutkan!') {
                event.preventDefault(); // Mencegah submit default
                
                const button = event.target.closest('button'); // Ambil tombol yang diklik
                const form = button.closest('form'); // Ambil form terkait
                
                if (!form) {
                    console.error('Form tidak ditemukan!');
                    return;
                }

                // FIX UTAMA: Jika tombol memiliki name & value (misal: status=verified),
                // kita harus menambahkannya manual ke form karena form.submit() JS tidak membawanya.
                if (button && button.name && button.value) {
                    // Cek apakah input hidden sudah ada (untuk mencegah duplikasi jika diklik berkali-kali)
                    let existingInput = form.querySelector(`input[name="${button.name}"]`);
                    
                    if (existingInput) {
                        existingInput.value = button.value;
                    } else {
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = button.name;
                        hiddenInput.value = button.value;
                        form.appendChild(hiddenInput);
                    }
                }

                Swal.fire({
                    title: 'Konfirmasi',
                    text: questionText,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0ea5e9', // Sky-500
                    cancelButtonColor: '#64748b', // Slate-500
                    confirmButtonText: confirmText,
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    focusCancel: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Mohon tunggu sebentar',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => { Swal.showLoading(); }
                        });
                        form.submit();
                    }
                });
            }

            /**
             * Manual Alert Trigger
             */
            window.showAlert = function(type, message) {
                Swal.fire({
                    icon: type,
                    title: type.charAt(0).toUpperCase() + type.slice(1),
                    text: message,
                    confirmButtonColor: '#0ea5e9'
                });
            }
        </script>
    </body>
</html>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LoFo') }}</title>

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

                // 3. Cek Validation Errors (Optional: Jika ingin popup error validasi form)
                @if($errors->any())
                    Swal.fire({
                        icon: 'warning',
                        title: 'Periksa Kembali Form',
                        html: '<ul class="text-left text-sm">@foreach($errors->all() as $error)<li>• {{ $error }}</li>@endforeach</ul>',
                        confirmButtonColor: '#f43f5e'
                    });
                @endif
            });

            // --- GLOBAL FUNCTIONS (Bisa dipanggil di view manapun) ---

            /**
             * Universal Confirmation Dialog
             * Cara pakai di tombol: onclick="confirmSubmit(event, 'Yakin hapus?')"
             */
            window.confirmSubmit = function(event, questionText = 'Apakah Anda yakin?', confirmText = 'Ya, Lanjutkan!') {
                event.preventDefault(); // Mencegah submit form langsung
                const form = event.target.closest('form'); // Mencari form terdekat
                
                if (!form) {
                    console.error('Form tidak ditemukan!');
                    return;
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
                        // Tampilkan loading saat proses submit
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
             * Cara pakai: onclick="showAlert('info', 'Pesan info disini')"
             */
            window.showAlert = function(type, message) {
                Swal.fire({
                    icon: type, // 'success', 'error', 'warning', 'info', 'question'
                    title: type.charAt(0).toUpperCase() + type.slice(1),
                    text: message,
                    confirmButtonColor: '#0ea5e9'
                });
            }
        </script>
    </body>
</html>
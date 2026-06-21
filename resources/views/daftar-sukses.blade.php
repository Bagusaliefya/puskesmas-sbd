<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendaftaran Berhasil - {{ config('app.name') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        h1, h2, h3, h4 { font-family: 'Manrope', system-ui, sans-serif; }
        @media print {
            body * { visibility: hidden; }
            #print-area, #print-area * { visibility: visible; }
            #print-area { position: absolute; left: 0; top: 0; width: 100%; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-[#f8f9ff] min-h-screen flex flex-col">
    <div class="flex items-center bg-white/80 backdrop-blur-md shadow-sm sticky top-0 z-30 border-b border-[#00685f]/5 no-print px-6 py-3">
        <a href="/" class="text-xl font-heading font-extrabold text-[#00685f] no-underline">{{ config('app.name') }}</a>
    </div>

    <div class="flex-1 flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-lg">
            <div class="bg-white rounded-3xl shadow-soft border border-[#00685f]/5" id="print-area">
                <div class="text-center py-10 px-6 md:px-8">
                    <div class="w-20 h-20 rounded-full bg-[#10b981]/10 flex items-center justify-center mx-auto mb-4 no-print">
                        <svg class="h-10 w-10 text-[#10b981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                    </div>

                    <h1 class="font-heading text-2xl font-extrabold text-[#0d1c2f]">Pendaftaran Berhasil</h1>
                    <p class="font-body text-sm text-[#0d1c2f]/60 mt-1 mb-6">Silakan menunggu antrean Anda dipanggil.</p>

                    <div class="w-full max-w-sm mx-auto">
                        <table class="w-full text-left">
                            <tr>
                                <td class="font-body font-semibold text-sm text-[#0d1c2f]/70 py-2 w-32">No. Antrean</td>
                                <td class="font-heading text-lg font-extrabold text-[#00685f] py-2">#{{ str_pad($pendaftaran->id_pendaftaran, 3, '0', STR_PAD_LEFT) }}</td>
                            </tr>
                            <tr>
                                <td class="font-body font-semibold text-sm text-[#0d1c2f]/70 py-2">Nama Pasien</td>
                                <td class="font-body text-sm text-[#0d1c2f] py-2">{{ $pendaftaran->pasien->nama_pasien }}</td>
                            </tr>
                            <tr>
                                <td class="font-body font-semibold text-sm text-[#0d1c2f]/70 py-2">Tanggal Daftar</td>
                                <td class="font-body text-sm text-[#0d1c2f] py-2">{{ \Carbon\Carbon::parse($pendaftaran->tanggal_daftar)->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td class="font-body font-semibold text-sm text-[#0d1c2f]/70 py-2">Keluhan</td>
                                <td class="font-body text-sm text-[#0d1c2f] py-2">{{ $pendaftaran->keluhan ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="border-t border-[#00685f]/10 my-6"></div>
                    <p class="font-body text-xs text-[#0d1c2f]/40">Simpan bukti ini untuk registrasi lebih lanjut.</p>

                    <div class="flex flex-col sm:flex-row gap-3 mt-6 w-full no-print">
                        <button onclick="window.print()" class="flex-1 bg-[#00685f] text-white font-body font-semibold text-sm py-3.5 rounded-2xl hover:bg-[#005a52] transition-all duration-300 shadow-lg shadow-[#00685f]/20 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            Cetak
                        </button>
                        <a href="{{ route('landing') }}" class="flex-1 flex items-center justify-center border-2 border-[#00685f]/20 text-[#0d1c2f]/60 font-body font-semibold text-sm py-3.5 rounded-2xl hover:bg-[#00685f]/5 hover:border-[#00685f]/40 transition-all duration-300">Kembali ke Beranda</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-[#233144] text-white/40 py-5 no-print">
        <div class="max-w-7xl mx-auto px-4">
            <p class="font-body text-sm text-center">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

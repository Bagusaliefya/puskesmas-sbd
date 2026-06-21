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
        #print-area, #print-resep { display: none; }
    </style>
</head>
<body class="bg-[#f8f9ff] min-h-screen flex flex-col">
    <div class="flex items-center bg-white/80 backdrop-blur-md shadow-sm sticky top-0 z-30 border-b border-[#00685f]/5 px-6 py-3" id="topbar">
        <a href="/" class="text-xl font-heading font-extrabold text-[#00685f] no-underline">{{ config('app.name') }}</a>
    </div>

    <div class="flex-1 flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-lg">
            <div class="bg-white rounded-3xl shadow-soft border border-[#00685f]/5" id="kartu-sukses">
                <div class="text-center py-10 px-6 md:px-8">
                    <div class="w-20 h-20 rounded-full bg-[#10b981]/10 flex items-center justify-center mx-auto mb-4">
                        <svg class="h-10 w-10 text-[#10b981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                    </div>

                    <h1 class="font-heading text-2xl font-extrabold text-[#0d1c2f]">Pendaftaran Berhasil</h1>
                    <p class="font-body text-sm text-[#0d1c2f]/60 mt-1 mb-6">Silakan menunggu antrean Anda dipanggil.</p>

                    <div class="w-full max-w-sm mx-auto">
                        <table class="w-full text-left">
                            <tr>
                                <td class="font-body font-semibold text-sm text-[#0d1c2f]/70 py-2 w-32">No. Antrean</td>
                                <td class="font-heading text-lg font-extrabold text-[#00685f] py-2">#{{ str_pad($pendaftaran->no_antrian ?? $pendaftaran->id_pendaftaran, 3, '0', STR_PAD_LEFT) }}</td>
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

                    @if($pendaftaran->pemeriksaan && $pendaftaran->pemeriksaan->resep)
                    <div class="border-t border-[#00685f]/10 my-6"></div>
                    <div class="text-left">
                        <h2 class="font-heading font-bold text-base text-[#0d1c2f] mb-3">Resep Obat</h2>
                        <p class="font-body text-xs text-[#0d1c2f]/60 mb-2">Dokter: {{ $pendaftaran->pemeriksaan->dokter?->pegawai?->nama_pegawai ?? '-' }}</p>
                        <p class="font-body text-xs text-[#0d1c2f]/60 mb-3">Diagnosa: {{ $pendaftaran->pemeriksaan->diagnosa ?? '-' }}</p>
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-[#00685f]/10">
                                    <th class="font-semibold py-2 text-[#0d1c2f]/70">Obat</th>
                                    <th class="font-semibold py-2 text-[#0d1c2f]/70">Jumlah</th>
                                    <th class="font-semibold py-2 text-[#0d1c2f]/70">Dosis</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendaftaran->pemeriksaan->resep->detailResep as $dr)
                                <tr class="border-b border-[#00685f]/5">
                                    <td class="py-2">{{ $dr->obat->nama_obat ?? '-' }}</td>
                                    <td class="py-2">{{ $dr->jumlah }}</td>
                                    <td class="py-2">{{ $dr->dosis ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif

                    <div class="border-t border-[#00685f]/10 my-6"></div>
                    <p class="font-body text-xs text-[#0d1c2f]/40">Simpan bukti ini untuk melihat resep dan registrasi lebih lanjut.</p>

                    <div class="flex flex-col sm:flex-row gap-3 mt-6 w-full">
                        <button onclick="cetakBukti()" class="flex-1 bg-[#00685f] text-white font-body font-semibold text-sm py-3.5 rounded-2xl hover:bg-[#005a52] transition-all duration-300 shadow-lg shadow-[#00685f]/20 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            Cetak Bukti
                        </button>
                        <a href="{{ route('landing') }}" class="flex-1 flex items-center justify-center border-2 border-[#00685f]/20 text-[#0d1c2f]/60 font-body font-semibold text-sm py-3.5 rounded-2xl hover:bg-[#00685f]/5 hover:border-[#00685f]/40 transition-all duration-300">Beranda</a>
                    </div>
                </div>
            </div>

            <div id="print-area" style="max-width:320px;margin:0 auto;font-family:'Inter',system-ui,sans-serif">
                <div style="text-align:center;padding-top:4px">
                    <div style="font-size:10px;letter-spacing:2px;text-transform:uppercase;color:#00685f;font-weight:700">{{ config('app.name') }}</div>
                    <div style="font-size:8px;color:#888;margin-top:2px">BUKTI PENDAFTARAN ANTREAN</div>
                </div>
                <div style="border-top:1px dashed #999;margin:8px 0"></div>
                <div style="text-align:center">
                    <div style="font-size:9px;color:#888;text-transform:uppercase;letter-spacing:1px">No. Antrean</div>
                    <div style="font-size:36px;font-weight:800;color:#00685f;letter-spacing:2px;line-height:1.1">#{{ str_pad($pendaftaran->no_antrian ?? $pendaftaran->id_pendaftaran, 3, '0', STR_PAD_LEFT) }}</div>
                </div>
                <div style="border-top:1px dashed #999;margin:8px 0"></div>
                <table style="width:100%;font-size:10px;line-height:1.8">
                    <tr><td style="color:#888;width:60px">Pasien</td><td style="color:#222">: {{ $pendaftaran->pasien->nama_pasien }}</td></tr>
                    <tr><td style="color:#888">Tanggal</td><td style="color:#222">: {{ \Carbon\Carbon::parse($pendaftaran->tanggal_daftar)->format('d/m/Y') }}</td></tr>
                    <tr><td style="color:#888;vertical-align:top">Keluhan</td><td style="color:#222">: {{ Str::limit($pendaftaran->keluhan ?? '-', 40) }}</td></tr>
                </table>
                <div style="border-top:1px dashed #999;margin:8px 0"></div>
                <div style="font-size:8px;text-align:center;color:#999;line-height:1.4">
                    Cek resep: {{ route('cek-resep') }}<br>
                    No. Antrean #{{ str_pad($pendaftaran->no_antrian ?? $pendaftaran->id_pendaftaran, 3, '0', STR_PAD_LEFT) }}
                </div>
                @if($pendaftaran->pemeriksaan && $pendaftaran->pemeriksaan->resep)
                <div style="border-top:1px dashed #999;margin:8px 0"></div>
                <div>
                    <div style="font-size:9px;font-weight:700;color:#00685f;margin-bottom:4px">RESEP OBAT</div>
                    <div style="font-size:8px;color:#555;margin-bottom:4px">Dokter: {{ $pendaftaran->pemeriksaan->dokter?->pegawai?->nama_pegawai ?? '-' }}</div>
                    <table style="width:100%;font-size:8px;border-collapse:collapse">
                        <thead>
                            <tr style="border-bottom:1px solid #ccc">
                                <th style="text-align:left;padding:2px;color:#888">Obat</th>
                                <th style="text-align:center;padding:2px;color:#888;width:24px">Jml</th>
                                <th style="text-align:left;padding:2px;color:#888">Dosis</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendaftaran->pemeriksaan->resep->detailResep as $dr)
                            <tr style="border-bottom:1px solid #eee">
                                <td style="padding:2px;color:#222">{{ $dr->obat->nama_obat ?? '-' }}</td>
                                <td style="text-align:center;padding:2px;color:#222">{{ $dr->jumlah }}</td>
                                <td style="padding:2px;color:#555">{{ $dr->dosis ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>

    <footer class="bg-[#233144] text-white/40 py-5" id="footer">
        <div class="max-w-7xl mx-auto px-4">
            <p class="font-body text-sm text-center">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </footer>

    <script>
    function cetakBukti() {
        var konten = document.getElementById('print-area').innerHTML;
        var body = document.body;
        var simpan = body.innerHTML;
        body.innerHTML = '<style>@page{margin:0;size:80mm 200mm}body{margin:0;padding:16px;font-family:Inter,system-ui,sans-serif;font-size:10px;color:#222}</style><div style="max-width:320px;margin:0 auto">' + konten + '</div>';
        window.print();
        body.innerHTML = simpan;
    }
    </script>
</body>
</html>

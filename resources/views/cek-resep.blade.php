<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="puskesmas">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cek Resep - {{ config('app.name') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,0&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        h1, h2, h3 { font-family: 'Manrope', system-ui, sans-serif; }
        #print-resep { display: none; }
    </style>
</head>
<body class="bg-[#f8f9ff] min-h-screen flex flex-col">
    <div class="flex items-center px-6 py-3">
        <a href="/" class="inline-flex items-center gap-2 text-sm font-semibold text-[#0d1c2f]/40 hover:text-[#00685f] no-underline transition-colors">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5m7-7-7 7 7 7"/>
            </svg>
            Kembali
        </a>
    </div>

    <div class="flex-1 flex items-center justify-center px-4 pb-12">
        <div class="w-full max-w-sm">
            <div class="text-center mb-8">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#00685f] to-[#008f7a] flex items-center justify-center mx-auto mb-4 shadow-lg shadow-[#00685f]/25 ring-4 ring-[#00685f]/10">
                    <span class="material-symbols-outlined text-white text-3xl" style="font-variation-settings:'FILL' 1,'wght' 400">medication</span>
                </div>
                <h1 class="text-2xl font-extrabold text-[#0d1c2f]">Cek Resep Obat</h1>
                <p class="text-sm text-[#0d1c2f]/50 mt-1.5">Masukkan No. Antrean untuk melihat resep.</p>
            </div>

            @if (session('error'))
            <div class="mb-4 p-4 rounded-2xl text-sm font-medium text-center" style="background:oklch(58% 0.112 28 / .08);color:oklch(48% 0.112 28)">
                {{ session('error') }}
            </div>
            @endif

            @isset($resep)
            <div class="bg-white rounded-3xl shadow-lg shadow-black/5 border border-[#00685f]/10 p-5 mb-5" id="kartu-resep">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs text-[#0d1c2f]/40 font-medium uppercase tracking-wide">Resep Obat</span>
                    <span class="text-lg font-extrabold text-[#00685f]">#{{ str_pad($pendaftaran->no_antrian, 3, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="border-t border-[#00685f]/10 pt-3 space-y-3">
                    @forelse($resep->detailResep as $dr)
                    <div class="flex items-start gap-3">
                        <div class="w-1.5 h-1.5 rounded-full bg-[#00685f] mt-2 shrink-0"></div>
                        <div>
                            <div class="flex items-baseline gap-2">
                                <span class="text-sm font-semibold text-[#0d1c2f]">{{ $dr->obat->nama_obat ?? '-' }}</span>
                                <span class="text-xs text-[#0d1c2f]/40">×{{ $dr->jumlah }}</span>
                            </div>
                            @if($dr->dosis)
                            <p class="text-xs text-[#0d1c2f]/50 mt-0.5">{{ $dr->dosis }}</p>
                            @endif
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-[#0d1c2f]/40 italic text-center py-2">Belum ada obat dalam resep.</p>
                    @endforelse
                </div>
                <div class="border-t border-[#00685f]/10 mt-4 pt-3 space-y-1.5">
                    @if($resep->pemeriksaan->dokter)
                    <div class="flex items-center gap-2 text-xs text-[#0d1c2f]/50">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21v-2a4 4 0 00-4-4H9a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        {{ $resep->pemeriksaan->dokter->pegawai->nama_pegawai ?? '-' }}
                    </div>
                    @endif
                    @if($resep->pemeriksaan->diagnosa)
                    <div class="flex items-center gap-2 text-xs text-[#0d1c2f]/50">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        {{ $resep->pemeriksaan->diagnosa }}
                    </div>
                    @endif
                </div>
                <button onclick="cetakResep()" class="w-full mt-4 font-semibold text-sm py-3 rounded-2xl text-white bg-[#00685f] hover:bg-[#005a52] transition-all border-none shadow-lg shadow-[#00685f]/20 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak Resep
                </button>
            </div>

            <div id="print-resep" style="max-width:320px;margin:0 auto;font-family:'Inter',system-ui,sans-serif">
                <div style="text-align:center;padding-top:4px">
                    <div style="font-size:10px;letter-spacing:2px;text-transform:uppercase;color:#00685f;font-weight:700">{{ config('app.name') }}</div>
                    <div style="font-size:8px;color:#888;margin-top:2px">RESEP OBAT</div>
                </div>
                <div style="border-top:1px dashed #999;margin:8px 0"></div>
                <div style="text-align:center">
                    <div style="font-size:9px;color:#888;text-transform:uppercase;letter-spacing:1px">No. Antrean</div>
                    <div style="font-size:28px;font-weight:800;color:#00685f;letter-spacing:2px;line-height:1.1">#{{ str_pad($pendaftaran->no_antrian, 3, '0', STR_PAD_LEFT) }}</div>
                </div>
                <div style="border-top:1px dashed #999;margin:8px 0"></div>
                <table style="width:100%;font-size:10px;line-height:1.8">
                    @if($resep->pemeriksaan->dokter)
                    <tr><td style="color:#888;width:60px">Dokter</td><td style="color:#222">: {{ $resep->pemeriksaan->dokter->pegawai->nama_pegawai ?? '-' }}</td></tr>
                    @endif
                    @if($resep->pemeriksaan->diagnosa)
                    <tr><td style="color:#888;vertical-align:top">Diagnosa</td><td style="color:#222">: {{ $resep->pemeriksaan->diagnosa }}</td></tr>
                    @endif
                    <tr><td style="color:#888">Tanggal</td><td style="color:#222">: {{ \Carbon\Carbon::parse($pendaftaran->tanggal_daftar)->format('d/m/Y') }}</td></tr>
                </table>
                <div style="border-top:1px dashed #999;margin:8px 0"></div>
                <div>
                    <div style="font-size:9px;font-weight:700;color:#00685f;margin-bottom:4px">DAFTAR OBAT</div>
                    <table style="width:100%;font-size:9px;border-collapse:collapse">
                        <thead>
                            <tr style="border-bottom:1px solid #ccc">
                                <th style="text-align:left;padding:3px 2px;color:#888">Obat</th>
                                <th style="text-align:center;padding:3px 2px;color:#888;width:24px">Jml</th>
                                <th style="text-align:left;padding:3px 2px;color:#888">Dosis</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($resep->detailResep as $dr)
                            <tr style="border-bottom:1px solid #eee">
                                <td style="padding:3px 2px;color:#222">{{ $dr->obat->nama_obat ?? '-' }}</td>
                                <td style="text-align:center;padding:3px 2px;color:#222">{{ $dr->jumlah }}</td>
                                <td style="padding:3px 2px;color:#555">{{ $dr->dosis ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($resep->created_at)
                    <div style="font-size:7px;color:#aaa;margin-top:6px;text-align:center">Dicetak: {{ now()->format('d/m/Y H:i') }}</div>
                    @endif
                </div>
            </div>
            @endisset

            <div class="bg-white rounded-3xl shadow-lg shadow-black/5 border border-[#00685f]/5 p-6">
                <form method="POST" action="{{ route('cek-resep.cari') }}">
                    @csrf
                    <div class="flex items-center gap-3 bg-[#f8f9ff] rounded-2xl px-4 border border-[#00685f]/10 focus-within:border-[#00685f]/40 focus-within:ring-2 focus-within:ring-[#00685f]/10 transition-all">
                        <span class="text-[#0d1c2f]/20 text-lg">#</span>
                        <input type="number" name="no_antrian" min="1" placeholder="Masukkan No. Antrean" class="flex-1 bg-transparent text-sm py-3.5 focus:outline-none" value="{{ old('no_antrian') }}" required>
                    </div>
                    @error('no_antrian') <p class="text-xs mt-2 px-1" style="color:oklch(58% 0.112 28)">{{ $message }}</p> @enderror
                    <button type="submit" class="w-full font-semibold text-sm py-3.5 rounded-2xl text-white bg-[#00685f] hover:bg-[#005a52] transition-all border-none mt-4 shadow-lg shadow-[#00685f]/20">
                        Cari Resep
                    </button>
                </form>
            </div>

            <p class="text-xs text-center text-[#0d1c2f]/30 mt-6">Hanya untuk pendaftaran hari ini.</p>
        </div>
    </div>

    <script>
    function cetakResep() {
        var konten = document.getElementById('print-resep').innerHTML;
        var body = document.body;
        var simpan = body.innerHTML;
        body.innerHTML = '<style>@page{margin:0;size:80mm 200mm}body{margin:0;padding:16px;font-family:Inter,system-ui,sans-serif;font-size:10px;color:#222}</style><div style="max-width:320px;margin:0 auto">' + konten + '</div>';
        window.print();
        body.innerHTML = simpan;
    }
    </script>
</body>
</html>

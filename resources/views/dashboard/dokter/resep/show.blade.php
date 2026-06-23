@extends('layouts.app')
@section('title', 'Detail Resep')
@push('styles')
<style>
@media print {
    @page { size: A4; margin: 1.5cm; }
    .app-sidebar, .app-navbar, .no-print, footer { display: none !important; }
    .ml-64 { margin-left: 0 !important; }
    .flex-grow { padding: 0 !important; }
    body { background: white !important; font-size: 12pt; color: #000 !important; }
    .print-area { visibility: visible; width: 100%; }
    .print-header { display: block !important; }
    .tonal-card { box-shadow: none !important; border: none !important; break-inside: avoid; background: transparent !important; padding: 0 !important; }
    .tonal-card [style*="padding"] { padding: 0 !important; }
    .table td, .table th { color: #000 !important; border-color: #ccc !important; padding: 8px 4px !important; }
    .table th { background: #f5f5f5 !important; text-transform: none !important; letter-spacing: normal !important; }
    a { text-decoration: none !important; color: #000 !important; }
    .material-symbols-outlined { display: none !important; }
}
</style>
@endpush
@section('content')
<div class="print-area">
    <div class="tonal-card" style="border-color:oklch(93% 0.006 268 / .5)">
        <div style="padding:1.5rem">
            <div class="flex items-center justify-between mb-4 no-print">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-success/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-success" style="font-size:24px">prescriptions</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold" style="color:oklch(12% 0.028 262)">Detail Resep</h1>
                        <p style="color:oklch(20% 0.024 262 / .6)">{{ $resep->pemeriksaan->pendaftaran->pasien->nama_pasien ?? '-' }}</p>
                    </div>
                </div>
                <button onclick="window.print()" class="btn-ghost-action">
                    <span class="material-symbols-outlined" style="font-size:20px">print</span>
                    Cetak
                </button>
            </div>
            <div class="print-header text-center mb-6 hidden print:block">
                <h2 class="text-xl font-bold" style="color:oklch(12% 0.028 262)">{{ config('app.name') }}</h2>
                <p style="color:oklch(20% 0.024 262 / .6)">Detail Resep Obat</p>
                <hr class="my-2" style="border-color:oklch(93% 0.006 268 / .5)">
            </div>
            <table class="table">
                <tr><td class="font-semibold w-40 py-4">Pasien</td><td class="font-medium py-4">{{ $resep->pemeriksaan->pendaftaran->pasien->nama_pasien ?? '-' }}</td></tr>
                <tr><td class="font-semibold py-4">Dokter</td><td class="py-4">{{ $resep->pemeriksaan?->dokter?->pegawai?->nama_pegawai ?? '-' }}</td></tr>
                <tr><td class="font-semibold py-4">Diagnosa</td><td class="py-4">{{ $resep->pemeriksaan->diagnosa ?? '-' }}</td></tr>
            </table>

            <h2 class="font-bold mt-8 mb-4 flex items-center gap-2" style="color:oklch(12% 0.028 262)">
                <span class="material-symbols-outlined" style="font-size:20px">checklist</span>
                Daftar Obat
            </h2>
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead><tr><th>Obat</th><th>Jumlah</th><th>Dosis</th></tr></thead>
                    <tbody>
                        @foreach($resep->detailResep as $dr)
                        <tr>
                            <td>{{ $dr->obat->nama_obat ?? '-' }}</td>
                            <td>{{ $dr->jumlah }}</td>
                            <td>{{ $dr->dosis ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="btn-group mt-4 no-print">
                <a href="{{ route('dokter.pemeriksaan.show', $resep->id_pemeriksaan) }}" class="btn-ghost-action">
                    <span class="material-symbols-outlined" style="font-size:20px">arrow_back</span>
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')
@section('title', 'Detail Pemeriksaan')
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
    .tonal-card .p-6 { padding: 0 !important; }
    .table td, .table th { color: #000 !important; border-color: #ccc !important; padding: 8px 4px !important; }
    .table th { background: #f5f5f5 !important; text-transform: none !important; letter-spacing: normal !important; }
    a { text-decoration: none !important; color: #000 !important; }
    .material-symbols-outlined { display: none !important; }
}
</style>
@endpush
@section('content')
<div class="print-area">
    <div class="tonal-card mb-8" style="border-color:oklch(93% 0.006 268 / .5)">
        <div class="p-6">
            <div class="flex items-center justify-between no-print mb-6">
                <h1 class="text-2xl font-bold flex items-center gap-2" style="color:oklch(12% 0.028 262)">
                    <span class="material-symbols-outlined">visibility</span>
                    Detail Pemeriksaan
                </h1>
                <button onclick="window.print()" class="btn-ghost-action">
                    <span class="material-symbols-outlined">print</span>
                    Cetak
                </button>
            </div>
            <div class="print-header text-center mb-6 hidden print:block">
                <h2 class="text-xl font-bold">{{ config('app.name') }}</h2>
                <p class="text-sm">Detail Pemeriksaan</p>
                <hr class="my-2">
            </div>
            <table class="table">
                <tr><td class="font-semibold w-40 py-4">Pasien</td><td class="font-medium py-4">{{ $pemeriksaan->pendaftaran->pasien->nama_pasien ?? '-' }}</td></tr>
                <tr><td class="font-semibold py-4">Dokter</td><td class="py-4">{{ $pemeriksaan->dokter?->pegawai?->nama_pegawai ?? '-' }}</td></tr>
                <tr><td class="font-semibold py-4">Tanggal Periksa</td><td class="py-4">{{ \Carbon\Carbon::parse($pemeriksaan->tanggal_periksa)->format('d M Y H:i') }}</td></tr>
                <tr><td class="font-semibold py-4">Sumber</td><td class="py-4">@if($pemeriksaan->pendaftaran->tipe_pendaftaran === 'mandiri') Mandiri @else Petugas @endif</td></tr>
                <tr><td class="font-semibold py-4">Keluhan</td><td class="py-4">{{ $pemeriksaan->pendaftaran->keluhan ?? '-' }}</td></tr>
                <tr><td class="font-semibold py-4">Diagnosa</td><td class="py-4 whitespace-pre-wrap">{{ $pemeriksaan->diagnosa ?? '-' }}</td></tr>
            </table>
        </div>
    </div>

    @if($pemeriksaan->resep)
    <div class="tonal-card mb-8" style="border-color:oklch(93% 0.006 268 / .5)">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold flex items-center gap-2" style="color:oklch(12% 0.028 262)">
                    <span class="material-symbols-outlined">prescriptions</span>
                    Resep Obat
                </h2>
                <button class="btn-danger-action no-print" onclick="document.getElementById('hapus-resep').showModal()">
                    <span class="material-symbols-outlined">delete</span>
                    Hapus Resep
                </button>
                <dialog id="hapus-resep" class="modal">
                    <div class="modal-box text-center py-10 px-8">
                        <div class="w-16 h-16 rounded-full bg-error/10 flex items-center justify-center mx-auto mb-4">
                            <span class="material-symbols-outlined text-4xl text-error">warning</span>
                        </div>
                        <h3 class="text-lg font-bold mb-1">Hapus Resep?</h3>
                        <p class="text-sm mb-6" style="color:oklch(20% 0.024 262 / .6)">Yakin ingin menghapus resep ini? Stok obat akan dikembalikan.</p>
                        <form method="POST" action="{{ route('dokter.resep.destroy', $pemeriksaan->resep->id_resep) }}">
                            @csrf @method('DELETE')
                            <div class="flex justify-center gap-2">
                                <button type="button" class="btn-ghost-action" onclick="document.getElementById('hapus-resep').close()">Batal</button>
                                <button class="btn-danger-action">Ya, Hapus</button>
                            </div>
                        </form>
                    </div>
                    <form method="dialog" class="modal-backdrop"><button></button></form>
                </dialog>
            </div>
            <table class="table">
                <thead><tr><th style="color:oklch(20% 0.024 262 / .6)">Obat</th><th style="color:oklch(20% 0.024 262 / .6)">Jumlah</th><th style="color:oklch(20% 0.024 262 / .6)">Dosis</th></tr></thead>
                <tbody>
                    @foreach($pemeriksaan->resep->detailResep as $dr)
                    <tr>
                        <td>{{ $dr->obat->nama_obat ?? '-' }}</td>
                        <td>{{ $dr->jumlah }}</td>
                        <td>{{ $dr->dosis ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="tonal-card mb-6" style="border-color:oklch(93% 0.006 268 / .5)">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined" style="color:oklch(20% 0.024 262 / .6)">medication</span>
                    <p style="color:oklch(20% 0.024 262 / .6)">Belum ada resep untuk pemeriksaan ini.</p>
                </div>
                <a href="{{ route('dokter.resep.create', $pemeriksaan->id_pemeriksaan) }}" class="btn-primary-action no-print">
                    <span class="material-symbols-outlined">prescriptions</span>
                    Buat Resep
                </a>
            </div>
        </div>
    </div>
    @endif
    <div class="btn-group no-print">
        <a href="{{ route('dokter.pemeriksaan.index') }}" class="btn-ghost-action">
            <span class="material-symbols-outlined">arrow_back</span>
            Kembali
        </a>
        <a href="{{ route('dokter.pemeriksaan.edit', $pemeriksaan->id_pemeriksaan) }}" class="btn-secondary-action">
            <span class="material-symbols-outlined">edit</span>
            Edit Diagnosa
        </a>
        <button class="btn-danger-action" onclick="document.getElementById('hapus-pemeriksaan').showModal()">
            <span class="material-symbols-outlined">delete</span>
            Hapus Pemeriksaan
        </button>
        <dialog id="hapus-pemeriksaan" class="modal">
            <div class="modal-box text-center py-10 px-8">
                <div class="w-16 h-16 rounded-full bg-error/10 flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-4xl text-error">warning</span>
                </div>
                <h3 class="text-lg font-bold mb-1">Hapus Pemeriksaan?</h3>
                <p class="text-sm mb-1" style="color:oklch(20% 0.024 262 / .6)">Semua data terkait (resep) juga akan dihapus dan stok obat dikembalikan.</p>
                <p class="text-xs text-error/70 mb-6">Tindakan ini tidak bisa dibatalkan.</p>
                <form method="POST" action="{{ route('dokter.pemeriksaan.destroy', $pemeriksaan->id_pemeriksaan) }}">
                    @csrf @method('DELETE')
                    <div class="flex justify-center gap-2">
                        <button type="button" class="btn-ghost-action" onclick="document.getElementById('hapus-pemeriksaan').close()">Batal</button>
                        <button class="btn-danger-action">Ya, Hapus</button>
                    </div>
                </form>
            </div>
            <form method="dialog" class="modal-backdrop"><button></button></form>
        </dialog>
    </div>
</div>
@endsection

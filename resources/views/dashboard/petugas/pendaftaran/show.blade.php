@extends('layouts.app')
@section('title', 'Detail Pendaftaran')
@section('content')
<div class="tonal-card mb-8">
    <div class="p-6">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-14 h-14 rounded-full flex items-center justify-center" style="background:oklch(42% 0.086 187 / .1)">
                <span class="material-symbols-outlined text-3xl" style="color:oklch(42% 0.086 187)">visibility</span>
            </div>
            <div>
                <h1 class="text-2xl font-bold" style="color:oklch(12% 0.028 262)">Detail Pendaftaran</h1>
                <p class="text-sm" style="color:oklch(20% 0.024 262 / .6)">{{ $pendaftaran->tanggal_daftar }}</p>
            </div>
        </div>
        <table class="table">
            <tr><td class="font-semibold w-36 py-4">Pasien</td><td class="font-medium py-4">{{ $pendaftaran->pasien->nama_pasien ?? '-' }}</td></tr>
            <tr><td class="font-semibold py-4">Tanggal Daftar</td><td class="py-4">{{ $pendaftaran->tanggal_daftar }}</td></tr>
            <tr><td class="font-semibold py-4">Keluhan</td><td class="py-4">{{ $pendaftaran->keluhan ?? '-' }}</td></tr>
            <tr><td class="font-semibold py-4">Petugas</td><td class="py-4">@if($pendaftaran->tipe_pendaftaran === 'mandiri') <span class="badge badge-accent">Mandiri</span> @else {{ $pendaftaran->petugas?->pegawai?->nama_pegawai ?? '-' }} @endif</td></tr>
        </table>
    </div>
</div>

@if($pendaftaran->pemeriksaan)
<div class="tonal-card mb-8">
    <div class="p-6">
        <h2 class="text-lg font-bold flex items-center gap-2 mb-4" style="color:oklch(12% 0.028 262)">
            <span class="material-symbols-outlined" style="color:oklch(42% 0.086 187)">stethoscope</span>
            Pemeriksaan
        </h2>
        <table class="table">
            <tr><td class="font-semibold w-36 py-4">Dokter</td><td class="py-4">{{ $pendaftaran->pemeriksaan?->dokter?->pegawai?->nama_pegawai ?? '-' }}</td></tr>
            <tr><td class="font-semibold py-4">Diagnosa</td><td class="py-4">{{ $pendaftaran->pemeriksaan->diagnosa ?? '-' }}</td></tr>
            <tr><td class="font-semibold py-4">Tanggal Periksa</td><td class="py-4">{{ $pendaftaran->pemeriksaan->tanggal_periksa }}</td></tr>
        </table>

        @if($pendaftaran->pemeriksaan->resep)
        <h3 class="font-semibold mt-6 mb-3 flex items-center gap-2" style="color:oklch(12% 0.028 262)">
            <span class="material-symbols-outlined">medication</span>
            Resep Obat
        </h3>
        <table class="table table-sm">
            <thead><tr><th>Obat</th><th>Jumlah</th><th>Dosis</th></tr></thead>
            <tbody>
                @foreach($pendaftaran->pemeriksaan->resep->detailResep as $dr)
                <tr>
                    <td class="py-3">{{ $dr->obat->nama_obat ?? '-' }}</td>
                    <td class="py-3">{{ $dr->jumlah }}</td>
                    <td class="py-3">{{ $dr->dosis ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
@endif
<div class="btn-group">
    <a href="{{ route('petugas.pendaftaran.index') }}" class="btn-ghost-action">
        <span class="material-symbols-outlined">arrow_back</span>
        Kembali
    </a>
    @unless($pendaftaran->pemeriksaan)
    <a href="{{ route('petugas.pendaftaran.edit', $pendaftaran->id_pendaftaran) }}" class="btn-secondary-action">
        <span class="material-symbols-outlined">edit</span>
        Edit
    </a>
    <button class="btn-danger-action" onclick="document.getElementById('hapus-pendaftaran').showModal()">
        <span class="material-symbols-outlined">delete</span>
        Hapus
    </button>
    <dialog id="hapus-pendaftaran" class="modal">
        <div class="modal-box text-center py-10 px-8">
            <div class="w-16 h-16 rounded-full bg-error/10 flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-4xl text-error">warning</span>
            </div>
            <h3 class="text-lg font-bold mb-1">Hapus Pendaftaran?</h3>
            <p class="text-sm mb-1" style="color:oklch(20% 0.024 262 / .6)">Yakin ingin menghapus pendaftaran <strong>{{ $pendaftaran->pasien->nama_pasien ?? 'pasien' }}</strong>?</p>
            <p class="text-xs text-error/70 mb-6">Data ini tidak bisa dikembalikan.</p>
            <form method="POST" action="{{ route('petugas.pendaftaran.destroy', $pendaftaran->id_pendaftaran) }}">
                @csrf @method('DELETE')
                <div class="flex justify-center gap-2">
                    <button type="button" class="btn-ghost-action" onclick="document.getElementById('hapus-pendaftaran').close()">Batal</button>
                    <button class="btn-danger-action">Ya, Hapus</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop"><button></button></form>
    </dialog>
    @endunless
</div>
@endsection

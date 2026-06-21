@extends('layouts.app')
@section('title', 'Riwayat Pemeriksaan')
@section('content')
<div>
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('dokter.pemeriksaan.index') }}" class="btn-ghost-action rounded-full w-10 h-10 flex items-center justify-center no-underline">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold flex items-center gap-2" style="color:oklch(12% 0.028 262)">
                <span class="material-symbols-outlined">history</span>
                Riwayat Pemeriksaan
            </h1>
            <p class="text-sm" style="color:oklch(20% 0.024 262 / .6)">{{ $pasien->nama_pasien }} ({{ $pasien->no_hp ?? '-' }})</p>
        </div>
    </div>

    @forelse($riwayat as $p)
    <div class="tonal-card mb-4" style="border-color:oklch(93% 0.006 268 / .5)">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <span class="font-semibold">{{ \Carbon\Carbon::parse($p->tanggal_daftar)->format('d M Y') }}</span>
                    <span class="text-sm ml-3" style="color:oklch(20% 0.024 262 / .6)">{{ $p->pemeriksaan->dokter?->pegawai?->nama_pegawai ?? '-' }}</span>
                </div>
                <a href="{{ route('dokter.pemeriksaan.show', $p->pemeriksaan->id_pemeriksaan) }}" class="btn-ghost-action">
                    <span class="material-symbols-outlined">visibility</span>
                </a>
            </div>
            <div class="space-y-2">
                <p><span class="font-medium">Keluhan:</span> {{ $p->keluhan ?? '-' }}</p>
                <p><span class="font-medium">Diagnosa:</span> {{ $p->pemeriksaan->diagnosa ?? '-' }}</p>
                @if($p->pemeriksaan->resep && $p->pemeriksaan->resep->detailResep->count() > 0)
                <div>
                    <span class="font-medium">Resep:</span>
                    <ul class="list-disc list-inside text-sm" style="color:oklch(20% 0.024 262 / .7)">
                        @foreach($p->pemeriksaan->resep->detailResep as $dr)
                        <li>{{ $dr->obat->nama_obat ?? '-' }} ({{ $dr->jumlah }}x) {{ $dr->dosis ? '- '.$dr->dosis : '' }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="tonal-card p-6 text-center" style="color:oklch(20% 0.024 262 / .6)">
        Belum ada riwayat pemeriksaan untuk pasien ini.
    </div>
    @endforelse
</div>
@endsection

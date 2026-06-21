@extends('layouts.app')
@section('title', 'Pemeriksaan')
@section('content')
<div class="page-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
    <h1 class="text-2xl font-bold flex items-center gap-2" style="color:oklch(12% 0.028 262)">
        <span class="material-symbols-outlined">stethoscope</span>
        Pemeriksaan
    </h1>
</div>

<div class="tonal-card p-4 mb-8" style="border-color:oklch(93% 0.006 268 / .5)">
    <div class="flex flex-wrap items-end gap-4">
        <div class="form-control flex-1">
            <label class="label"><span class="label-text font-medium">Cari Riwayat Pasien</span></label>
            <select class="select select-bordered w-full" style="border-color:oklch(93% 0.006 268 / .5)" onchange="if(this.value) window.location.href='{{ url('dashboard/dokter/pemeriksaan/riwayat') }}/'+this.value">
                <option value="">-- Pilih Pasien --</option>
                @foreach(\App\Models\Pasien::orderBy('nama_pasien')->get() as $p)
                <option value="{{ $p->id_pasien }}">{{ $p->nama_pasien }} ({{ $p->no_hp ?? '-' }})</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

@if($daftarPeriksa->count() > 0)
<div class="tonal-card mb-8" style="border-color:oklch(93% 0.006 268 / .5)">
    <div class="p-6">
        <h2 class="text-lg font-semibold mb-4 flex items-center gap-2" style="color:oklch(12% 0.028 262)">
            <span class="material-symbols-outlined">schedule</span>
            Menunggu Periksa
        </h2>
        <div class="table-wrap">
            <table class="table w-full">
                <thead><tr><th style="color:oklch(20% 0.024 262 / .6)">No</th><th style="color:oklch(20% 0.024 262 / .6)">Pasien</th><th style="color:oklch(20% 0.024 262 / .6)">Keluhan</th><th style="color:oklch(20% 0.024 262 / .6)">Sumber</th><th style="color:oklch(20% 0.024 262 / .6)">Aksi</th></tr></thead>
                <tbody>
                    @foreach($daftarPeriksa as $p)
                    <tr>
                        <td><span class="font-mono font-semibold">#{{ str_pad($p->no_antrian ?? $loop->iteration, 3, '0', STR_PAD_LEFT) }}</span></td>
                        <td class="font-medium">{{ $p->pasien->nama_pasien ?? '-' }}</td>
                        <td>
                            {{ Str::limit($p->keluhan ?? '', 40) ?: '-' }}
                            @if($p->keluhan && substr_count($p->keluhan, "\n") > 0)
                            <span class="badge badge-info badge-xs">Rangkap</span>
                            @endif
                        </td>
                        <td>@if($p->tipe_pendaftaran === 'mandiri') <span class="badge badge-accent badge-sm">Mandiri</span> @else <span class="badge badge-ghost badge-sm">Petugas</span> @endif</td>
                        <td>
                            <a href="{{ route('dokter.pemeriksaan.create', $p->id_pendaftaran) }}" class="btn-primary-action btn-mobile-full">
                                <span class="material-symbols-outlined">stethoscope</span>
                                Periksa
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<div class="tonal-card" style="border-color:oklch(93% 0.006 268 / .5)">
    <div class="p-6">
        <h2 class="text-lg font-semibold mb-4 flex items-center gap-2" style="color:oklch(12% 0.028 262)">
            <span class="material-symbols-outlined">history</span>
            Riwayat Pemeriksaan Hari Ini
        </h2>
        <div class="table-wrap">
            <table class="table w-full">
                <thead><tr><th style="color:oklch(20% 0.024 262 / .6)">Pasien</th><th style="color:oklch(20% 0.024 262 / .6)">Diagnosa</th><th style="color:oklch(20% 0.024 262 / .6)">Resep</th><th style="color:oklch(20% 0.024 262 / .6)">Aksi</th></tr></thead>
                <tbody>
                    @forelse($pemeriksaan as $p)
                    <tr>
                        <td class="font-medium">
                            {{ $p->pendaftaran?->pasien?->nama_pasien ?? '-' }}
                            @if($p->pendaftaran?->pasien)
                            <a href="{{ route('dokter.pemeriksaan.riwayat', $p->pendaftaran->pasien->id_pasien) }}" class="btn-ghost-action text-xs ml-1" title="Lihat riwayat">
                                <span class="material-symbols-outlined" style="font-size:14px">history</span>
                            </a>
                            @endif
                        </td>
                        <td>{{ $p->diagnosa ? Str::limit($p->diagnosa, 30) : '-' }}</td>
                        <td>
                            @if($p->resep)
                                <span class="badge badge-success badge-sm">Ada</span>
                            @else
                                <span class="badge badge-ghost badge-sm">Belum</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('dokter.pemeriksaan.show', $p->id_pemeriksaan) }}" class="btn-ghost-action">
                                <span class="material-symbols-outlined">visibility</span>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-4" style="color:oklch(20% 0.024 262 / .6)">Belum ada pemeriksaan hari ini</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

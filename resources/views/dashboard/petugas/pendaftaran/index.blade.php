@extends('layouts.app')
@section('title', 'Pendaftaran')
@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-bold flex items-center gap-2" style="color:oklch(12% 0.028 262)">
            <span class="material-symbols-outlined">how_to_reg</span>
            Data Pendaftaran
        </h1>
        @if(!$startDate && !$endDate)
        <p class="text-sm mt-1" style="color:oklch(20% 0.024 262 / .6)">Menampilkan pendaftaran hari ini</p>
        @endif
    </div>
    <div class="flex gap-2">
        <button class="btn-secondary-action" onclick="document.getElementById('modal-filter-tanggal').showModal()">
            <span class="material-symbols-outlined">date_range</span>
            Filter Tanggal
        </button>
        <a href="{{ route('petugas.pendaftaran.create') }}" class="btn-primary-action">
            <span class="material-symbols-outlined">add</span>
            Daftarkan Pasien
        </a>
    </div>
</div>

<dialog id="modal-filter-tanggal" class="modal">
    <div class="modal-box">
        <h3 class="text-lg font-bold mb-4">Filter Tanggal</h3>
        <form method="GET">
            <div class="form-control mb-3">
                <label class="label"><span class="label-text font-medium">Dari Tanggal</span></label>
                <input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="input input-bordered w-full">
            </div>
            <div class="form-control mb-4">
                <label class="label"><span class="label-text font-medium">Sampai Tanggal</span></label>
                <input type="date" name="end_date" value="{{ $endDate ?? '' }}" class="input input-bordered w-full">
            </div>
            <div class="flex justify-end gap-2">
                @if($startDate || $endDate)
                <a href="{{ route('petugas.pendaftaran.index') }}" class="btn-ghost-action">Reset</a>
                @endif
                <button type="button" class="btn-ghost-action" onclick="document.getElementById('modal-filter-tanggal').close()">Batal</button>
                <button type="submit" class="btn-primary-action">Terapkan</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button></button></form>
</dialog>

@if($startDate || $endDate)
<div class="mb-4 flex items-center gap-2 text-sm" style="color:oklch(20% 0.024 262 / .6)">
    <span class="material-symbols-outlined" style="font-size:16px">event</span>
    Rentang: {{ $startDate ?? 'Awal' }} — {{ $endDate ?? 'Sekarang' }}
    <a href="{{ route('petugas.pendaftaran.index') }}" class="badge badge-ghost badge-sm ml-2 cursor-pointer">Hapus filter</a>
</div>
@endif
<div class="tonal-card overflow-hidden">
    <div class="p-1">
    <div class="table-wrap">
    <table class="table w-full">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pasien</th>
                <th>Keluhan</th>
                <th>Petugas</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pendaftaran as $i => $d)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td class="font-medium">{{ $d->pasien->nama_pasien ?? '-' }}</td>
                <td>
                    @if($d->keluhan)
                        {{ Str::of($d->keluhan)->replace("\n", ' / ')->limit(30) }}
                    @else
                        <span style="color:oklch(20% 0.024 262 / .4)">-</span>
                    @endif
                    @if($d->keluhan && substr_count($d->keluhan, "\n") > 0)
                    <span class="badge badge-info badge-xs">Rangkap</span>
                    @endif
                </td>
                <td>@if($d->tipe_pendaftaran === 'mandiri') <span class="badge badge-accent badge-sm">Mandiri</span> @else {{ $d->petugas?->pegawai?->nama_pegawai ?? '-' }} @endif</td>
                <td>
                    @if($d->pemeriksaan)
                        <span class="badge badge-success badge-sm">Diperiksa</span>
                    @elseif($d->dipanggil_at)
                        <span class="badge badge-info badge-sm">Dipanggil</span>
                    @else
                        <span class="badge badge-warning badge-sm">Menunggu</span>
                    @endif
                </td>
                <td>
                    <div class="flex gap-1 items-center">
                        <a href="{{ route('petugas.pendaftaran.show', $d->id_pendaftaran) }}" class="btn-ghost-action">
                            <span class="material-symbols-outlined" style="font-size:16px">visibility</span>
                        </a>
                        @if(!$d->pemeriksaan && !$d->dipanggil_at)
                        <button class="btn-primary-action text-sm py-1 px-3" onclick="document.getElementById('panggil-{{ $d->id_pendaftaran }}').showModal()">
                            <span class="material-symbols-outlined" style="font-size:16px">campaign</span> Panggil
                        </button>
                        <dialog id="panggil-{{ $d->id_pendaftaran }}" class="modal">
                            <div class="modal-box text-center py-10 px-8">
                                <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-4">
                                    <span class="material-symbols-outlined text-4xl text-primary">campaign</span>
                                </div>
                                <h3 class="text-lg font-bold mb-1">Panggil Pasien?</h3>
                                <p class="text-sm mb-6" style="color:oklch(20% 0.024 262 / .6)">Yakin ingin memanggil <strong>{{ $d->pasien->nama_pasien ?? 'pasien' }}</strong>?</p>
                                <form method="POST" action="{{ route('petugas.pendaftaran.panggil', $d->id_pendaftaran) }}">
                                    @csrf
                                    <div class="flex justify-center gap-2">
                                        <button type="button" class="btn-ghost-action" onclick="document.getElementById('panggil-{{ $d->id_pendaftaran }}').close()">Batal</button>
                                        <button class="btn-primary-action">Ya, Panggil</button>
                                    </div>
                                </form>
                            </div>
                            <form method="dialog" class="modal-backdrop"><button></button></form>
                        </dialog>
                        @elseif($d->dipanggil_at && !$d->pemeriksaan)
                        <span class="text-xs px-2 py-1 rounded-full" style="background:oklch(42% 0.086 187 / .1);color:oklch(42% 0.086 187)">
                            {{ $d->dipanggil_at->format('H:i') }}
                        </span>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center py-8" style="color:oklch(20% 0.024 262 / .6)">Belum ada pendaftaran hari ini</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>
<div class="mt-6">{{ $pendaftaran->links() }}</div>
@endsection

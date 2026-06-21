@extends('layouts.app')
@section('title', 'Detail Pasien')
@section('content')
<div class="flex items-center gap-4 mb-8">
    <a href="{{ route('pasien.index') }}" class="btn btn-ghost btn-circle">
        <span class="material-symbols-outlined">arrow_back</span>
    </a>
    <span class="material-symbols-outlined" style="color:oklch(52% 0.1 182);font-size:2.5rem">personal_injury</span>
    <div>
        <h1 class="text-2xl font-bold">{{ $pasien->nama_pasien }}</h1>
        <p style="color:oklch(20% 0.024 262 / .6)">Detail informasi pasien</p>
    </div>
    <div class="ml-auto">
        <span class="badge badge-lg
            @if($pasien->jenis_kelamin === 'L') badge-info
            @elseif($pasien->jenis_kelamin === 'P') badge-secondary
            @else badge-ghost @endif">
            {{ $pasien->jenis_kelamin === 'L' ? 'Laki-laki' : ($pasien->jenis_kelamin === 'P' ? 'Perempuan' : '-') }}
        </span>
    </div>
</div>

<div class="tonal-card p-6 mb-8" style="border:1px solid oklch(93% 0.006 268 / .5)">
    <table class="table">
        <tr><td class="font-semibold w-36 py-4">Tanggal Lahir</td><td class="py-4">{{ $pasien->tanggal_lahir ? \Carbon\Carbon::parse($pasien->tanggal_lahir)->format('d M Y') : '-' }}</td></tr>
        <tr><td class="font-semibold py-4">No. HP</td><td class="py-4">{{ $pasien->no_hp ?? '-' }}</td></tr>
        <tr><td class="font-semibold py-4">Golongan Darah</td><td class="py-4">@if($pasien->golongan_darah) <span class="badge badge-ghost">{{ $pasien->golongan_darah }}</span> @else - @endif</td></tr>
        <tr><td class="font-semibold py-4">Alamat</td><td class="py-4">{{ $pasien->alamat ?? '-' }}</td></tr>
    </table>
    <div class="btn-group mt-6 pt-6" style="border-top:1px solid oklch(93% 0.006 268 / .5)">
        <a href="{{ route('pasien.edit', $pasien) }}" class="btn-secondary-action">
            <span class="material-symbols-outlined">edit</span>
            Edit
        </a>
        <button class="btn-danger-action" onclick="document.getElementById('hapus-pasien').showModal()">
            <span class="material-symbols-outlined">delete</span>
            Hapus
        </button>
        <a href="{{ route('pasien.index') }}" class="btn-ghost-action">
            <span class="material-symbols-outlined">arrow_back</span>
            Kembali
        </a>
    </div>
    <dialog id="hapus-pasien" class="modal">
        <div class="modal-box text-center py-10 px-8">
            <div class="w-16 h-16 rounded-full bg-error/10 flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-4xl text-error">warning</span>
            </div>
            <h3 class="text-lg font-bold mb-1">Hapus Pasien?</h3>
            <p class="text-sm text-base-content/70 mb-1">Yakin ingin menghapus <strong>{{ $pasien->nama_pasien }}</strong>?</p>
            <p class="text-xs text-error/70">Semua data pendaftaran &amp; pemeriksaan pasien ini juga akan dihapus.</p>
            <form method="POST" action="{{ route('pasien.destroy', $pasien) }}" class="mt-6 flex justify-center gap-2">
                @csrf @method('DELETE')
                <button type="button" class="btn-ghost-action" onclick="document.getElementById('hapus-pasien').close()">Batal</button>
                <button class="btn-danger-action">Ya, Hapus</button>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop"><button></button></form>
    </dialog>
</div>

@if($pasien->pendaftaran->count() > 0)
<div class="tonal-card p-6" style="border:1px solid oklch(93% 0.006 268 / .5)">
    <h2 class="text-lg font-semibold flex items-center gap-2 mb-4">
        <span class="material-symbols-outlined" style="color:oklch(52% 0.1 182);font-size:1.5rem">history</span>
        Riwayat Pendaftaran
    </h2>
    <div class="overflow-x-auto">
        <table class="table w-full">
            <thead>
                <tr><th>Tanggal</th><th>Keluhan</th><th>Petugas</th><th>Sumber</th><th>Status</th></tr>
            </thead>
            <tbody>
                @foreach($pasien->pendaftaran as $d)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($d->tanggal_daftar)->format('d M Y') }}</td>
                    <td>{{ Str::limit($d->keluhan ?? '', 30) ?: '-' }}</td>
                    <td>@if($d->tipe_pendaftaran === 'mandiri') <span class="badge badge-accent badge-sm">Mandiri</span> @else {{ $d->petugas?->pegawai?->nama_pegawai ?? '-' }} @endif</td>
                    <td>@if($d->tipe_pendaftaran === 'mandiri') <span class="badge badge-accent badge-sm">Mandiri</span> @else <span class="badge badge-ghost badge-sm">Petugas</span> @endif</td>
                    <td>
                        @if($d->pemeriksaan)
                            <span class="badge badge-success badge-sm">Diperiksa</span>
                        @else
                            <span class="badge badge-warning badge-sm">Menunggu</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection

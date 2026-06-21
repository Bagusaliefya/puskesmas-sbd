@extends('layouts.app')
@section('title', 'Data Pegawai')
@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <h1 class="text-2xl font-bold flex items-center gap-2" style="color:oklch(12% 0.028 262)">
        <span class="material-symbols-outlined" style="color:oklch(42% 0.086 187)">badge</span>
        Data Pegawai
    </h1>
    <a href="{{ route('pegawai.create') }}" class="btn-primary-action">
        <span class="material-symbols-outlined text-lg">add</span>
        Tambah Pegawai
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <div class="tonal-card overflow-hidden">
        <div class="p-6 border-b flex items-center gap-2" style="border-color:oklch(93% 0.006 268 / .5)">
            <span class="material-symbols-outlined" style="color:oklch(52% 0.1 182)">badge</span>
            <h3 class="font-bold card-header">Data Petugas</h3>
        </div>
        <div class="table-wrap">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Loket</th>
                        <th>No. HP</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($petugas as $p)
                    <tr>
                        <td class="font-medium">{{ $p->pegawai->nama_pegawai }}</td>
                        <td>{{ $p->loket ?? '-' }}</td>
                        <td>{{ $p->pegawai->no_hp ?? '-' }}</td>
                        <td class="text-right">
                            <a href="{{ route('pegawai.show', $p->pegawai) }}" class="btn-ghost-action btn-xs" title="Detail">
                                <span class="material-symbols-outlined text-sm">visibility</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-8" style="color:oklch(20% 0.024 262 / .5)">Belum ada data petugas</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="tonal-card overflow-hidden">
        <div class="p-6 border-b flex items-center gap-2" style="border-color:oklch(93% 0.006 268 / .5)">
            <span class="material-symbols-outlined" style="color:oklch(42% 0.086 187)">stethoscope</span>
            <h3 class="font-bold card-header">Data Dokter</h3>
        </div>
        <div class="table-wrap">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Spesialisasi</th>
                        <th>No. HP</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dokter as $d)
                    <tr>
                        <td class="font-medium">{{ $d->pegawai->nama_pegawai }}</td>
                        <td>{{ $d->spesialisasi ?? '-' }}</td>
                        <td>{{ $d->pegawai->no_hp ?? '-' }}</td>
                        <td class="text-right">
                            <a href="{{ route('pegawai.show', $d->pegawai) }}" class="btn-ghost-action btn-xs" title="Detail">
                                <span class="material-symbols-outlined text-sm">visibility</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-8" style="color:oklch(20% 0.024 262 / .5)">Belum ada data dokter</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
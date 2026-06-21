@extends('layouts.app')
@section('title', 'Pencarian: ' . $q)
@section('content')
<div class="flex items-center gap-4 mb-8">
    <span class="material-symbols-outlined" style="color:oklch(42% 0.086 187);font-size:2.5rem">search</span>
    <div>
        <h1 class="text-2xl font-bold">Hasil Pencarian</h1>
        <p class="text-sm" style="color:oklch(20% 0.024 262 / .6)">Menampilkan hasil untuk "{{ $q }}"</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="tonal-card overflow-hidden">
        <div class="p-4 border-b flex items-center gap-2" style="border-color:oklch(93% 0.006 268 / .5)">
            <span class="material-symbols-outlined" style="color:oklch(52% 0.1 182)">personal_injury</span>
            <h3 class="font-bold">Pasien</h3>
        </div>
        @if($pasien->count() > 0)
        <div class="table-wrap">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>No. HP</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pasien as $p)
                    <tr>
                        <td class="font-medium">{{ $p->nama_pasien }}</td>
                        <td>{{ $p->no_hp ?? '-' }}</td>
                        <td>
                            <div class="action-group">
                                <a href="{{ route('pasien.show', $p) }}" class="btn-ghost-action">
                                    <span class="material-symbols-outlined text-base">visibility</span> Detail
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-6 text-center" style="color:oklch(20% 0.024 262 / .5)">
            <span class="material-symbols-outlined text-4xl">person_search</span>
            <p class="mt-2 text-sm">Pasien tidak ditemukan</p>
        </div>
        @endif
    </div>

    <div class="tonal-card overflow-hidden">
        <div class="p-4 border-b flex items-center gap-2" style="border-color:oklch(93% 0.006 268 / .5)">
            <span class="material-symbols-outlined" style="color:oklch(42% 0.086 187)">medication</span>
            <h3 class="font-bold">Obat</h3>
        </div>
        @if($obat->count() > 0)
        <div class="table-wrap">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($obat as $o)
                    <tr>
                        <td class="font-medium">{{ $o->nama_obat }}</td>
                        <td><span class="badge badge-sm {{ $o->stok > 0 ? 'badge-success' : 'badge-warning' }}">{{ $o->stok }}</span></td>
                        <td>
                            <div class="action-group">
                                <a href="{{ route('obat.edit', $o) }}" class="btn-secondary-action">
                                    <span class="material-symbols-outlined text-base">edit</span> Edit
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-6 text-center" style="color:oklch(20% 0.024 262 / .5)">
            <span class="material-symbols-outlined text-4xl">medication</span>
            <p class="mt-2 text-sm">Obat tidak ditemukan</p>
        </div>
        @endif
    </div>

    <div class="tonal-card overflow-hidden">
        <div class="p-4 border-b flex items-center gap-2" style="border-color:oklch(93% 0.006 268 / .5)">
            <span class="material-symbols-outlined" style="color:oklch(42% 0.086 187)">badge</span>
            <h3 class="font-bold">Pegawai</h3>
        </div>
        @if($pegawai->count() > 0)
        <div class="table-wrap">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pegawai as $p)
                    <tr>
                        <td class="font-medium">{{ $p->nama_pegawai }}</td>
                        <td>{{ $p->jabatan }}</td>
                        <td>
                            <div class="action-group">
                                <a href="{{ route('pegawai.show', $p) }}" class="btn-ghost-action">
                                    <span class="material-symbols-outlined text-base">visibility</span> Detail
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-6 text-center" style="color:oklch(20% 0.024 262 / .5)">
            <span class="material-symbols-outlined text-4xl">badge</span>
            <p class="mt-2 text-sm">Pegawai tidak ditemukan</p>
        </div>
        @endif
    </div>
</div>
@endsection

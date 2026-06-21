@extends('layouts.app')
@section('title', 'Data Pasien')
@section('content')
<div class="page-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <h1 class="text-2xl font-bold flex items-center gap-2" style="color:oklch(12% 0.028 262)">
        <span class="material-symbols-outlined" style="color:oklch(52% 0.1 182)">personal_injury</span>
        Data Pasien
    </h1>
    <div class="header-actions flex gap-2">
        <a href="{{ route('pasien.csv') }}" class="btn-secondary-action btn-mobile-full">
            <span class="material-symbols-outlined text-lg">download</span>
            <span class="btn-text">CSV</span>
        </a>
        <button class="btn-secondary-action btn-mobile-full" onclick="document.getElementById('modal-import-pasien').showModal()">
            <span class="material-symbols-outlined text-lg">upload</span>
            <span class="btn-text">Import CSV</span>
        </button>
        <a href="{{ route('pasien.create') }}" class="btn-primary-action btn-mobile-full">
            <span class="material-symbols-outlined text-lg">add</span>
            <span class="btn-text">Tambah Pasien</span>
        </a>
    </div>
</div>
<dialog id="modal-import-pasien" class="modal">
    <div class="modal-box">
        <h3 class="text-lg font-bold mb-1">Import Data Pasien</h3>
        <p class="text-sm mb-4" style="color:oklch(20% 0.024 262 / .6)">Upload file CSV dengan format: Nama, Tanggal Lahir, Jenis Kelamin (L/P), Alamat, No HP, Golongan Darah</p>
        <form method="POST" action="{{ route('pasien.import') }}" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file" accept=".csv,.txt" class="file-input file-input-bordered w-full mb-4" required>
            <div class="flex justify-end gap-2">
                <button type="button" class="btn-ghost-action" onclick="document.getElementById('modal-import-pasien').close()">Batal</button>
                <button type="submit" class="btn-primary-action">Import</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button></button></form>
</dialog>

<div class="tonal-card overflow-hidden">
    <div class="p-1">
    <div class="overflow-x-auto">
        <table class="table w-full">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Tanggal Lahir</th>
                    <th>Jenis Kelamin</th>
                    <th>No. HP</th>
                    <th>Gol. Darah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pasien as $p)
                <tr>
                    <td class="font-medium">{{ $p->nama_pasien }}</td>
                    <td>{{ $p->tanggal_lahir ?? '-' }}</td>
                    <td>
                        @if($p->jenis_kelamin === 'L')
                            <span class="badge badge-info badge-sm">Laki-laki</span>
                        @elseif($p->jenis_kelamin === 'P')
                            <span class="badge badge-secondary badge-sm">Perempuan</span>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $p->no_hp ?? '-' }}</td>
                    <td>
                        @if($p->golongan_darah)
                            <span class="badge badge-ghost badge-sm">{{ $p->golongan_darah }}</span>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('pasien.show', $p) }}" class="btn-ghost-action">
                            <span class="material-symbols-outlined text-base">visibility</span> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-8" style="color:oklch(20% 0.024 262 / .5)">Belum ada data pasien</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    </div>
</div>
<div class="mt-6">{{ $pasien->links() }}</div>
@endsection

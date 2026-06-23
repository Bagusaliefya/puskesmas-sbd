@extends('layouts.app')
@section('title', 'Data Obat')
@section('content')
<div class="page-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-bold flex items-center gap-2" style="color:oklch(12% 0.028 262)">
            <span class="material-symbols-outlined" style="color:oklch(63% 0.106 210)">medication</span>
            Data Obat
        </h1>
        @if($obatMenipis > 0)
        <p class="text-sm mt-1 flex items-center gap-1" style="color:oklch(58% 0.112 28)">
            <span class="material-symbols-outlined" style="font-size:16px">warning</span>
            {{ $obatMenipis }} obat dengan stok menipis
        </p>
        @endif
    </div>
    <div class="header-actions flex gap-2">
        <a href="{{ route('obat.csv') }}" class="btn-secondary-action btn-mobile-full">
            <span class="material-symbols-outlined text-lg">download</span>
            <span class="btn-text">CSV</span>
        </a>
        <button class="btn-secondary-action btn-mobile-full" onclick="document.getElementById('modal-import-obat').showModal()">
            <span class="material-symbols-outlined text-lg">upload</span>
            <span class="btn-text">Import CSV</span>
        </button>
        <a href="{{ route('obat.create') }}" class="btn-primary-action btn-mobile-full">
            <span class="material-symbols-outlined text-lg">add</span>
            <span class="btn-text">Tambah Obat</span>
        </a>
    </div>
</div>

@if($obatMenipis > 0)
<div class="tonal-card p-4 mb-6" style="border-color:oklch(58% 0.112 28 / 0.3);background:oklch(58% 0.112 28 / 0.05)">
    <div class="flex items-center gap-3">
        <span class="material-symbols-outlined" style="color:oklch(58% 0.112 28)">inventory_2</span>
        <div>
            <p class="font-medium" style="color:oklch(58% 0.112 28)">Stok Obat Menipis</p>
            <p class="text-sm" style="color:oklch(20% 0.024 262 / .6)">Beberapa obat sudah mencapai atau di bawah batas stok minimum. Segera lakukan pengadaan.</p>
        </div>
        <a href="{{ route('obat.create') }}" class="btn-primary-action ml-auto text-sm">
            <span class="material-symbols-outlined">add</span> Tambah Stok
        </a>
    </div>
</div>
@endif

<dialog id="modal-import-obat" class="modal">
    <div class="modal-box">
        <h3 class="text-lg font-bold mb-1">Import Data Obat</h3>
        <p class="text-sm mb-4" style="color:oklch(20% 0.024 262 / .6)">Upload file CSV dengan format: Nama Obat, Stok, Stok Minimum</p>
        <form method="POST" action="{{ route('obat.import') }}" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file" accept=".csv,.txt" class="file-input file-input-bordered w-full mb-4" required>
            <div class="flex justify-end gap-2">
                <button type="button" class="btn-ghost-action" onclick="document.getElementById('modal-import-obat').close()">Batal</button>
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
                    <th>Nama Obat</th>
                    <th>Stok</th>
                    <th>Stok Minimum</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($obat as $o)
                <tr>
                    <td class="font-medium">{{ $o->nama_obat }}</td>
                    <td>{{ $o->stok }}</td>
                    <td>{{ $o->stok_minimum }}</td>
                    <td>
                        @if($o->stokMenipis())
                            <span class="badge badge-sm badge-error">Menipis</span>
                        @else
                            <span class="badge badge-sm badge-success">Aman</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('obat.show', $o) }}" class="btn-ghost-action">
                            <span class="material-symbols-outlined text-base">visibility</span> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-8" style="color:oklch(20% 0.024 262 / .5)">Belum ada data obat</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    </div>
</div>
<div class="mt-6">{{ $obat->links() }}</div>
@endsection

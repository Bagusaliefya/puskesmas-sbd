@extends('layouts.app')
@section('title', 'Detail Obat')
@section('content')
<div class="flex items-center gap-4 mb-8">
    <a href="{{ route('obat.index') }}" class="btn btn-ghost btn-circle">
        <span class="material-symbols-outlined">arrow_back</span>
    </a>
    <span class="material-symbols-outlined" style="color:oklch(63% 0.106 210);font-size:2.5rem">medication</span>
    <div>
        <h1 class="text-2xl font-bold">{{ $obat->nama_obat }}</h1>
        <p style="color:oklch(20% 0.024 262 / .6)">Detail informasi obat</p>
    </div>
    <div class="ml-auto">
        @if($obat->stokMenipis())
            <span class="badge badge-lg badge-error">Stok Menipis</span>
        @else
            <span class="badge badge-lg badge-success">Stok Aman</span>
        @endif
    </div>
</div>

<div class="tonal-card p-6 mb-8" style="border:1px solid oklch(93% 0.006 268 / .5)">
    <table class="table">
        <tr><td class="font-semibold w-36 py-4">Nama Obat</td><td class="font-medium py-4">{{ $obat->nama_obat }}</td></tr>
        <tr><td class="font-semibold py-4">Stok</td><td class="py-4">{{ $obat->stok }}</td></tr>
        <tr><td class="font-semibold py-4">Stok Minimum</td><td class="py-4">{{ $obat->stok_minimum }}</td></tr>
        <tr><td class="font-semibold py-4">Status</td>
            <td class="py-4">
                @if($obat->stokMenipis())
                    <span class="badge badge-error">Menipis</span>
                @else
                    <span class="badge badge-success">Aman</span>
                @endif
            </td>
        </tr>
    </table>
    <div class="btn-group mt-6 pt-6" style="border-top:1px solid oklch(93% 0.006 268 / .5)">
        <a href="{{ route('obat.edit', $obat) }}" class="btn-secondary-action">
            <span class="material-symbols-outlined">edit</span>
            Edit
        </a>
        <button class="btn-danger-action" onclick="document.getElementById('hapus-obat').showModal()">
            <span class="material-symbols-outlined">delete</span>
            Hapus
        </button>
        <a href="{{ route('obat.index') }}" class="btn-ghost-action">
            <span class="material-symbols-outlined">arrow_back</span>
            Kembali
        </a>
    </div>
    <dialog id="hapus-obat" class="modal">
        <div class="modal-box text-center py-10 px-8">
            <div class="w-16 h-16 rounded-full bg-error/10 flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-4xl text-error">warning</span>
            </div>
            <h3 class="text-lg font-bold mb-1">Hapus Obat?</h3>
            <p class="text-sm text-base-content/70 mb-1">Yakin ingin menghapus <strong>{{ $obat->nama_obat }}</strong>?</p>
            <p class="text-xs text-error/70">Data yang terkait dengan obat ini juga akan dihapus.</p>
            <form method="POST" action="{{ route('obat.destroy', $obat) }}" class="mt-6 flex justify-center gap-2">
                @csrf @method('DELETE')
                <button type="button" class="btn-ghost-action" onclick="document.getElementById('hapus-obat').close()">Batal</button>
                <button class="btn-danger-action">Ya, Hapus</button>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop"><button></button></form>
    </dialog>
</div>
@endsection
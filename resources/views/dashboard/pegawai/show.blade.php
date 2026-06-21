@extends('layouts.app')
@section('title', 'Detail Pegawai')
@section('content')
<div class="tonal-card">
    <div class="p-6">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-14 h-14 rounded-full flex items-center justify-center" style="background:oklch(42% 0.086 187 / .1)">
                <span class="material-symbols-outlined text-3xl" style="color:oklch(42% 0.086 187)">person</span>
            </div>
            <div>
                <h1 class="text-2xl font-bold">Detail Pegawai</h1>
                <p class="text-sm" style="color:oklch(20% 0.024 262 / .6)">{{ $pegawai->jabatan }}</p>
            </div>
        </div>
        <div class="overflow-x-auto">
        <table class="table">
            <tr><td class="font-semibold w-40 py-4">Nama</td><td class="py-4">{{ $pegawai->nama_pegawai }}</td></tr>
            <tr><td class="font-semibold py-4">Jabatan</td><td class="py-4">{{ $pegawai->jabatan }}</td></tr>
            <tr><td class="font-semibold py-4">No. HP</td><td class="py-4">{{ $pegawai->no_hp ?? '-' }}</td></tr>
            <tr><td class="font-semibold py-4">Tanggal Masuk</td><td class="py-4">{{ $pegawai->tanggal_masuk ? \Carbon\Carbon::parse($pegawai->tanggal_masuk)->format('d M Y') : '-' }}</td></tr>
            <tr><td class="font-semibold py-4">Alamat</td><td class="py-4">{{ $pegawai->alamat ?? '-' }}</td></tr>
            <tr><td class="font-semibold py-4">Tipe</td>
                <td class="py-4">
                    @if($pegawai->petugas)
                        <span class="badge badge-warning">Petugas{{ $pegawai->petugas->loket ? ' ('.$pegawai->petugas->loket.')' : '' }}</span>
                    @endif
                    @if($pegawai->dokter)
                        <span class="badge badge-info">Dokter{{ $pegawai->dokter->spesialisasi ? ' ('.$pegawai->dokter->spesialisasi.')' : '' }}</span>
                    @endif
                </td>
            </tr>
        </table>
        </div>
        <div class="btn-group mt-8 pt-6 border-t" style="border-color:oklch(93% 0.006 268 / .5)">
            <a href="{{ route('pegawai.edit', $pegawai) }}" class="btn-secondary-action">
                <span class="material-symbols-outlined" style="font-size:1.25rem">edit</span>
                Edit
            </a>
            <button class="btn-danger-action" onclick="document.getElementById('hapus-pegawai').showModal()">
                <span class="material-symbols-outlined" style="font-size:1.25rem">delete</span>
                Hapus
            </button>
            <a href="{{ route('pegawai.index') }}" class="btn-ghost-action">
                <span class="material-symbols-outlined" style="font-size:1.25rem">arrow_back</span>
                Kembali
            </a>
        </div>
        <dialog id="hapus-pegawai" class="modal">
            <div class="modal-box text-center py-10 px-8">
                <div class="w-16 h-16 rounded-full bg-error/10 flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-4xl text-error">warning</span>
                </div>
                <h3 class="text-lg font-bold mb-1">Hapus Pegawai?</h3>
                <p class="text-sm text-base-content/70 mb-1">Yakin ingin menghapus <strong>{{ $pegawai->nama_pegawai }}</strong>?</p>
                <p class="text-xs text-error/70">Data yang terkait dengan pegawai ini juga akan dihapus.</p>
                <form method="POST" action="{{ route('pegawai.destroy', $pegawai) }}" class="mt-6 flex justify-center gap-2">
                    @csrf @method('DELETE')
                    <button type="button" class="btn-ghost-action" onclick="document.getElementById('hapus-pegawai').close()">Batal</button>
                    <button class="btn-danger-action">Ya, Hapus</button>
                </form>
            </div>
            <form method="dialog" class="modal-backdrop"><button></button></form>
        </dialog>
    </div>
</div>
@endsection

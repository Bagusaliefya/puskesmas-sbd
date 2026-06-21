@extends('layouts.app')
@section('title', 'Edit Obat')
@section('content')
<div>
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('obat.index') }}" class="btn btn-ghost btn-circle">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold flex items-center gap-2">
                <span class="material-symbols-outlined" style="color:oklch(42% 0.086 187)">edit</span>
                Edit Obat</h1>
            <p class="text-sm" style="color:oklch(20% 0.024 262 / .6)">Ubah data obat</p>
        </div>
    </div>

    <div class="tonal-card">
        <div class="p-6">
            <form method="POST" action="{{ route('obat.update', $obat) }}">
                @csrf @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-control md:col-span-2">
                        <label class="label">
                            <span class="label-text font-medium">Nama Obat <span class="text-error">*</span></span>
                        </label>
                        <input type="text" name="nama_obat" class="input input-bordered w-full @error('nama_obat') input-error @enderror" value="{{ old('nama_obat', $obat->nama_obat) }}" placeholder="Masukkan nama obat" required>
                        @error('nama_obat') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Stok <span class="text-error">*</span></span>
                        </label>
                        <input type="number" name="stok" class="input input-bordered w-full @error('stok') input-error @enderror" value="{{ old('stok', $obat->stok) }}" min="0" required>
                        @error('stok') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Stok Minimum <span class="text-error">*</span></span>
                        </label>
                        <input type="number" name="stok_minimum" class="input input-bordered w-full @error('stok_minimum') input-error @enderror" value="{{ old('stok_minimum', $obat->stok_minimum ?? 10) }}" min="0" required>
                        @error('stok_minimum') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Harga (Rp) <span class="text-error">*</span></span>
                        </label>
                        <input type="number" name="harga" class="input input-bordered w-full @error('harga') input-error @enderror" value="{{ old('harga', $obat->harga) }}" min="0" required>
                        @error('harga') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="btn-group mt-8 pt-6 border-t" style="border-color:oklch(93% 0.006 268 / .5)">
                    <button type="submit" class="btn-primary-action">Simpan</button>
                    <a href="{{ route('obat.index') }}" class="btn-ghost-action">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

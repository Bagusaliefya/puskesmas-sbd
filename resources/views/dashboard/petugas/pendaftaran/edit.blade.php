@extends('layouts.app')
@section('title', 'Edit Pendaftaran')
@section('content')
<div>
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('petugas.pendaftaran.index') }}" class="btn-ghost-action rounded-full w-10 h-10 flex items-center justify-center no-underline">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold flex items-center gap-2" style="color:oklch(12% 0.028 262)">
                <span class="material-symbols-outlined">edit</span>
                Edit Pendaftaran
            </h1>
            <p class="text-sm" style="color:oklch(20% 0.024 262 / .6)">{{ $pendaftaran->pasien->nama_pasien ?? '-' }} — {{ $pendaftaran->tanggal_daftar }}</p>
        </div>
    </div>

    <div class="tonal-card">
        <div class="p-6">
            <form method="POST" action="{{ route('petugas.pendaftaran.update', $pendaftaran->id_pendaftaran) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="updated_at" value="{{ $pendaftaran->updated_at }}">
                @error('stale_data') <div class="alert alert-error mb-4 p-3 rounded-lg text-sm">{{ $message }}</div> @enderror

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Pasien <span class="text-error">*</span></span>
                    </label>
                    <select name="id_pasien" class="select select-bordered w-full" style="border-color:oklch(93% 0.006 268 / .5)">
                        <option value="">-- Pilih Pasien --</option>
                        @foreach($pasienList as $p)
                        <option value="{{ $p->id_pasien }}" @selected(old('id_pasien', $pendaftaran->id_pasien) == $p->id_pasien)>{{ $p->nama_pasien }} ({{ $p->no_hp ?? '-' }})</option>
                        @endforeach
                    </select>
                    @error('id_pasien') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="form-control mt-4">
                    <label class="label">
                        <span class="label-text font-medium">Keluhan <span class="text-error">*</span></span>
                    </label>
                    <textarea name="keluhan" required class="textarea textarea-bordered w-full" rows="4" placeholder="Tuliskan keluhan pasien (wajib diisi)" style="border-color:oklch(93% 0.006 268 / .5)">{{ old('keluhan', $pendaftaran->keluhan) }}</textarea>
                    @error('keluhan') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="btn-group mt-8 pt-6" style="border-top:1px solid oklch(93% 0.006 268 / .5)">
                    <button type="submit" class="btn-primary-action">Simpan</button>
                    <a href="{{ route('petugas.pendaftaran.index') }}" class="btn-ghost-action">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

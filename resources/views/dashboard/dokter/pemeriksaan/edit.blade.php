@extends('layouts.app')
@section('title', 'Edit Pemeriksaan')
@section('content')
<div>
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('dokter.pemeriksaan.show', $pemeriksaan->id_pemeriksaan) }}" class="btn-ghost-action rounded-full w-10 h-10 flex items-center justify-center no-underline">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold flex items-center gap-2" style="color:oklch(12% 0.028 262)">
                <span class="material-symbols-outlined">edit_note</span>
                Edit Diagnosa
            </h1>
            <p class="text-sm" style="color:oklch(20% 0.024 262 / .6)">
                {{ $pemeriksaan->pendaftaran->pasien->nama_pasien ?? '-' }}
            </p>
        </div>
    </div>

    <div class="tonal-card">
        <div class="p-6">
            <form method="POST" action="{{ route('dokter.pemeriksaan.update', $pemeriksaan->id_pemeriksaan) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="updated_at" value="{{ $pemeriksaan->updated_at }}">
                @error('stale_data') <div class="alert alert-error mb-4 p-3 rounded-lg text-sm">{{ $message }}</div> @enderror

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Diagnosa</span>
                    </label>
                    <textarea name="diagnosa" class="textarea textarea-bordered w-full" rows="5" style="border-color:oklch(93% 0.006 268 / .5)">{{ old('diagnosa', $pemeriksaan->diagnosa) }}</textarea>
                    @error('diagnosa') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="btn-group mt-8 pt-6" style="border-top:1px solid oklch(93% 0.006 268 / .5)">
                    <button type="submit" class="btn-primary-action">Simpan</button>
                    <a href="{{ route('dokter.pemeriksaan.show', $pemeriksaan->id_pemeriksaan) }}" class="btn-ghost-action">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

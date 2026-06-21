@extends('layouts.app')
@section('title', 'Lakukan Pemeriksaan')
@section('content')
<div>
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('dokter.pemeriksaan.index') }}" class="btn btn-ghost btn-circle">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold flex items-center gap-2" style="color:oklch(12% 0.028 262)">
                <span class="material-symbols-outlined">stethoscope</span>
                Pemeriksaan Pasien
            </h1>
            <p class="text-sm" style="color:oklch(20% 0.024 262 / .6)">Catat hasil pemeriksaan</p>
        </div>
    </div>

    <div class="tonal-card mb-8" style="border-color:oklch(93% 0.006 268 / .5)">
        <div class="p-6">
            <h2 class="text-lg font-semibold mb-4" style="color:oklch(12% 0.028 262)">Data Pendaftaran</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center gap-4 p-4 bg-base-200 rounded-box">
                    <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center">
                        <span class="material-symbols-outlined" style="color:oklch(42% 0.086 187)">person</span>
                    </div>
                    <div>
                        <p class="text-xs" style="color:oklch(20% 0.024 262 / .6)">Pasien</p>
                        <p class="font-medium">{{ $pendaftaran->pasien->nama_pasien ?? '-' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 p-4 bg-base-200 rounded-box">
                    <div class="w-12 h-12 rounded-full bg-warning/10 flex items-center justify-center">
                        <span class="material-symbols-outlined" style="color:oklch(72% 0.166 70)">chat</span>
                    </div>
                    <div>
                        <p class="text-xs" style="color:oklch(20% 0.024 262 / .6)">Keluhan</p>
                        <p class="font-medium">{{ $pendaftaran->keluhan ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="tonal-card" style="border-color:oklch(93% 0.006 268 / .5)">
        <div class="p-6">
            <form method="POST" action="{{ route('dokter.pemeriksaan.store') }}">
                @csrf
                <input type="hidden" name="id_pendaftaran" value="{{ $pendaftaran->id_pendaftaran }}">

                <div class="grid grid-cols-1 gap-6">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Diagnosa <span class="text-error">*</span></span>
                        </label>
                        <textarea name="diagnosa" class="textarea textarea-bordered w-full @error('diagnosa') textarea-error @enderror" rows="5" placeholder="Tuliskan hasil diagnosa pemeriksaan..." required>{{ old('diagnosa') }}</textarea>
                        @error('diagnosa') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="btn-group mt-8 pt-6" style="border-top:1px solid oklch(93% 0.006 268 / .5)">
                    <button type="submit" class="btn-primary-action">Simpan Pemeriksaan</button>
                    <a href="{{ route('dokter.pemeriksaan.index') }}" class="btn-ghost-action">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

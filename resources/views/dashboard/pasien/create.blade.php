@extends('layouts.app')
@section('title', 'Tambah Pasien')
@section('content')
<div>
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('pasien.index') }}" class="btn btn-ghost btn-circle">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold flex items-center gap-2" style="color:oklch(12% 0.028 262)">
                <span class="material-symbols-outlined" style="color:oklch(52% 0.1 182)">person_add</span>
                Tambah Pasien</h1>
            <p class="text-sm" style="color:oklch(20% 0.024 262 / .6)">Lengkapi data pasien baru</p>
        </div>
    </div>

    <div class="tonal-card">
        <div class="p-6">
            <form method="POST" action="{{ route('pasien.store') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-control md:col-span-2">
                        <label class="label">
                            <span class="label-text font-medium">Nama Pasien <span class="text-error">*</span></span>
                        </label>
                        <input type="text" name="nama_pasien" class="input input-bordered w-full @error('nama_pasien') input-error @enderror" value="{{ old('nama_pasien') }}" placeholder="Masukkan nama lengkap" required pattern="[A-Za-z\s\.\']+" title="Hanya huruf yang diperbolehkan" oninput="this.value = this.value.replace(/[^A-Za-z\s\.\']/g, '')">
                        @error('nama_pasien') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Tanggal Lahir</span>
                        </label>
                        <input type="date" name="tanggal_lahir" class="input input-bordered w-full @error('tanggal_lahir') input-error @enderror" value="{{ old('tanggal_lahir') }}">
                        @error('tanggal_lahir') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Jenis Kelamin</span>
                        </label>
                        <select name="jenis_kelamin" class="select select-bordered w-full @error('jenis_kelamin') select-error @enderror">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L" @selected(old('jenis_kelamin') === 'L')>Laki-laki</option>
                            <option value="P" @selected(old('jenis_kelamin') === 'P')>Perempuan</option>
                        </select>
                        @error('jenis_kelamin') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">No. HP</span>
                        </label>
                        <input type="tel" name="no_hp" inputmode="numeric" pattern="[0-9]+" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="input input-bordered w-full @error('no_hp') input-error @enderror" value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx" title="Hanya angka" required>
                        @error('no_hp') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Golongan Darah</span>
                        </label>
                        <select name="golongan_darah" class="select select-bordered w-full @error('golongan_darah') select-error @enderror">
                            <option value="">Pilih Golongan Darah</option>
                            <option value="A" @selected(old('golongan_darah') === 'A')>A</option>
                            <option value="B" @selected(old('golongan_darah') === 'B')>B</option>
                            <option value="AB" @selected(old('golongan_darah') === 'AB')>AB</option>
                            <option value="O" @selected(old('golongan_darah') === 'O')>O</option>
                        </select>
                        @error('golongan_darah') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-control md:col-span-2">
                        <label class="label">
                            <span class="label-text font-medium">Alamat</span>
                        </label>
                        <textarea name="alamat" class="textarea textarea-bordered w-full @error('alamat') textarea-error @enderror" rows="3" placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                        @error('alamat') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="btn-group mt-8 pt-6 border-t" style="border-color:oklch(93% 0.006 268 / .5)">
                    <button type="submit" class="btn-primary-action">Simpan</button>
                    <a href="{{ route('pasien.index') }}" class="btn-ghost-action">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

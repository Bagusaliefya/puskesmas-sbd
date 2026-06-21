@extends('layouts.app')
@section('title', 'Edit Pegawai')
@section('content')
<div>
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('pegawai.index') }}" class="btn btn-ghost btn-circle">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold flex items-center gap-2">
                <span class="material-symbols-outlined" style="color:oklch(42% 0.086 187)">edit</span>
                Edit Pegawai</h1>
            <p class="text-sm" style="color:oklch(20% 0.024 262 / .6)">Ubah data pegawai</p>
        </div>
    </div>

    <div class="tonal-card">
        <div class="p-6">
            <form method="POST" action="{{ route('pegawai.update', $pegawai) }}">
                @csrf @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Nama Pegawai <span class="text-error">*</span></span>
                        </label>
                        <input type="text" name="nama_pegawai" class="input input-bordered w-full @error('nama_pegawai') input-error @enderror" value="{{ old('nama_pegawai', $pegawai->nama_pegawai) }}" placeholder="Masukkan nama lengkap" required pattern="[A-Za-z\s\.\']+" title="Hanya huruf yang diperbolehkan" oninput="this.value = this.value.replace(/[^A-Za-z\s\.\']/g, '')">
                        @error('nama_pegawai') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Jabatan <span class="text-error">*</span></span>
                        </label>
                        <input type="text" name="jabatan" class="input input-bordered w-full @error('jabatan') input-error @enderror" value="{{ old('jabatan', $pegawai->jabatan) }}" placeholder="Contoh: Petugas Loket, Dokter Umum" required pattern="[A-Za-z\s\.\']+" title="Hanya huruf yang diperbolehkan" oninput="this.value = this.value.replace(/[^A-Za-z\s\.\']/g, '')">
                        @error('jabatan') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">No. HP</span>
                        </label>
                        <input type="tel" name="no_hp" inputmode="numeric" pattern="[0-9]+" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="input input-bordered w-full @error('no_hp') input-error @enderror" value="{{ old('no_hp', $pegawai->no_hp) }}" placeholder="08xxxxxxxxxx" title="Hanya angka">
                        @error('no_hp') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Tanggal Masuk</span>
                        </label>
                        <input type="date" name="tanggal_masuk" class="input input-bordered w-full @error('tanggal_masuk') input-error @enderror" value="{{ old('tanggal_masuk', $pegawai->tanggal_masuk) }}">
                        @error('tanggal_masuk') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-control md:col-span-2">
                        <label class="label">
                            <span class="label-text font-medium">Alamat</span>
                        </label>
                        <textarea name="alamat" class="textarea textarea-bordered w-full @error('alamat') textarea-error @enderror" rows="3" placeholder="Masukkan alamat lengkap">{{ old('alamat', $pegawai->alamat) }}</textarea>
                        @error('alamat') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="divider mt-6 mb-6">Kategori Pegawai</div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Tipe <span class="text-error">*</span></span>
                        </label>
                        <select name="tipe" class="select select-bordered w-full @error('tipe') select-error @enderror" id="tipe" required>
                            <option value="petugas" {{ $pegawai->petugas ? 'selected' : '' }}>Petugas</option>
                            <option value="dokter" {{ $pegawai->dokter ? 'selected' : '' }}>Dokter</option>
                        </select>
                        @error('tipe') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Password Akun</span>
                        </label>
                        <input type="password" name="password" class="input input-bordered w-full @error('password') input-error @enderror" placeholder="Kosongkan jika tidak diubah">
                        @error('password') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <div class="form-control {{ $pegawai->petugas ? '' : 'hidden' }}" id="loket-field">
                            <label class="label">
                                <span class="label-text font-medium">Loket</span>
                            </label>
                            <input type="text" name="loket" class="input input-bordered w-full" value="{{ old('loket', $pegawai->petugas->loket ?? '') }}" placeholder="Contoh: Loket 1">
                        </div>
                        <div class="form-control {{ $pegawai->dokter ? '' : 'hidden' }}" id="spesialisasi-field">
                            <label class="label">
                                <span class="label-text font-medium">Spesialisasi</span>
                            </label>
                            <input type="text" name="spesialisasi" class="input input-bordered w-full" value="{{ old('spesialisasi', $pegawai->dokter->spesialisasi ?? '') }}" placeholder="Contoh: Umum, Gigi">
                        </div>
                    </div>
                </div>

                <div class="btn-group mt-8 pt-6 border-t" style="border-color:oklch(93% 0.006 268 / .5)">
                    <button type="submit" class="btn-primary-action">Simpan</button>
                    <a href="{{ route('pegawai.index') }}" class="btn-ghost-action">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.getElementById('tipe').addEventListener('change', function() {
    document.getElementById('loket-field').classList.toggle('hidden', this.value !== 'petugas');
    document.getElementById('spesialisasi-field').classList.toggle('hidden', this.value !== 'dokter');
});
</script>
@endsection

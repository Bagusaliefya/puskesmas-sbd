@extends('layouts.app')
@section('title', 'Pendaftaran Pasien')
@push('styles')
<style>
.tab-custom {
    flex: 1;
    text-align: center;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    font-size: 0.875rem;
    color: oklch(20% 0.024 262 / .6);
    cursor: pointer;
    text-decoration: none;
    transition: all 0.2s ease;
    border-radius: 0.625rem;
    margin: 4px;
}
.tab-custom:hover {
    color: oklch(42% 0.086 187);
    background: oklch(42% 0.086 187 / 0.06);
}
.tab-custom.tab-active {
    color: oklch(100% 0 0);
    background: linear-gradient(135deg, oklch(48% 0.09 187), oklch(38% 0.08 187));
    box-shadow: 0 2px 8px oklch(42% 0.086 187 / 0.25);
}
[data-theme="puskesmas-dark"] .tab-custom {
    color: oklch(90% 0.007 262 / .6);
}
[data-theme="puskesmas-dark"] .tab-custom:hover {
    color: oklch(68% 0.09 180);
    background: oklch(68% 0.09 180 / 0.1);
}
[data-theme="puskesmas-dark"] .tab-custom.tab-active {
    background: linear-gradient(135deg, oklch(55% 0.09 187), oklch(45% 0.08 187));
    box-shadow: 0 2px 8px oklch(42% 0.086 187 / 0.15);
}
</style>
@endpush
@section('content')
<div>
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('petugas.pendaftaran.index') }}" class="btn-ghost-action rounded-full w-10 h-10 flex items-center justify-center no-underline">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold flex items-center gap-2" style="color:oklch(12% 0.028 262)">
                <span class="material-symbols-outlined">app_registration</span>
                Pendaftaran Pasien
            </h1>
            <p class="text-sm" style="color:oklch(20% 0.024 262 / .6)">Daftarkan pasien untuk berobat</p>
        </div>
    </div>

    @if(!$status['bisa_daftar'])
    <div class="tonal-card mb-4">
        <div class="p-4 flex items-center gap-3 text-sm font-semibold" style="color:{{ $status['warna'] }}">
            <span class="material-symbols-outlined">{{ $status['icon'] }}</span>
            {{ $status['pesan'] }}
            <a href="{{ route('petugas.pendaftaran.index') }}" class="btn-ghost-action btn-xs ml-auto">Kembali</a>
        </div>
    </div>
    @endif

    <div class="tonal-card">
        <div class="p-6">
            <div class="flex mb-8 rounded-xl overflow-hidden" style="border:1px solid oklch(93% 0.006 268);background:oklch(97.5% 0.006 265)">
                <a class="tab-custom tab-active" id="tab-baru" onclick="switchTab('baru')">Pasien Baru</a>
                <a class="tab-custom" id="tab-existing" onclick="switchTab('existing')">Pasien Sudah Terdaftar</a>
            </div>

            <form method="POST" action="{{ route('petugas.pendaftaran.store') }}">
                @csrf

                <div id="form-baru">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Nama Lengkap <span class="text-error">*</span></span>
                        </label>
                        <input type="text" name="nama_pasien" class="input input-bordered w-full" value="{{ old('nama_pasien') }}" pattern="[A-Za-z\s\.\']+" title="Hanya huruf yang diperbolehkan" oninput="this.value = this.value.replace(/[^A-Za-z\s\.\']/g, '')" style="border-color:oklch(93% 0.006 268 / .5)">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-medium">Tanggal Lahir</span>
                            </label>
                            <input type="date" name="tanggal_lahir" class="input input-bordered w-full" value="{{ old('tanggal_lahir') }}" style="border-color:oklch(93% 0.006 268 / .5)">
                        </div>
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-medium">Jenis Kelamin</span>
                            </label>
                            <select name="jenis_kelamin" class="select select-bordered w-full" style="border-color:oklch(93% 0.006 268 / .5)">
                                <option value="">Pilih</option>
                                <option value="L" @selected(old('jenis_kelamin') === 'L')>Laki-laki</option>
                                <option value="P" @selected(old('jenis_kelamin') === 'P')>Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-medium">No. HP</span>
                            </label>
                            <input type="tel" name="no_hp" inputmode="numeric" pattern="[0-9]+" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="input input-bordered w-full" value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx" title="Hanya angka" style="border-color:oklch(93% 0.006 268 / .5)">
                        </div>
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-medium">Golongan Darah</span>
                            </label>
                            <select name="golongan_darah" class="select select-bordered w-full" style="border-color:oklch(93% 0.006 268 / .5)">
                                <option value="">Pilih</option>
                                <option value="A" @selected(old('golongan_darah') === 'A')>A</option>
                                <option value="B" @selected(old('golongan_darah') === 'B')>B</option>
                                <option value="AB" @selected(old('golongan_darah') === 'AB')>AB</option>
                                <option value="O" @selected(old('golongan_darah') === 'O')>O</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-control mt-4">
                        <label class="label">
                            <span class="label-text font-medium">Alamat</span>
                        </label>
                        <textarea name="alamat" class="textarea textarea-bordered w-full" rows="2" style="border-color:oklch(93% 0.006 268 / .5)">{{ old('alamat') }}</textarea>
                    </div>

                    <div class="form-control mt-4">
                        <label class="label">
                            <span class="label-text font-medium">Keluhan <span class="text-error">*</span></span>
                        </label>
                        <textarea name="keluhan" required class="textarea textarea-bordered w-full @error('keluhan') textarea-error @enderror" rows="4" placeholder="Tuliskan keluhan pasien (wajib diisi)" style="border-color:oklch(93% 0.006 268 / .5)">{{ old('keluhan') }}</textarea>
                        @error('keluhan') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div id="form-existing" class="hidden">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Pilih Pasien <span class="text-error">*</span></span>
                        </label>
                        <select name="id_pasien" class="select select-bordered w-full" style="border-color:oklch(93% 0.006 268 / .5)" disabled>
                            <option value="">-- Pilih Pasien --</option>
                            @foreach($pasienList as $p)
                            <option value="{{ $p->id_pasien }}" @selected(old('id_pasien') == $p->id_pasien)>{{ $p->nama_pasien }} ({{ $p->no_hp ?? '-' }})@if(in_array($p->id_pasien, $pendingToday)) [Pending]@endif</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-control mt-4">
                        <label class="label">
                            <span class="label-text font-medium">Keluhan <span class="text-error">*</span></span>
                        </label>
                        <textarea name="keluhan" required class="textarea textarea-bordered w-full @error('keluhan') textarea-error @enderror" rows="4" placeholder="Tuliskan keluhan pasien (wajib diisi)" style="border-color:oklch(93% 0.006 268 / .5)" disabled>{{ old('keluhan') }}</textarea>
                        @error('keluhan') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="btn-group mt-8 pt-6" style="border-top:1px solid oklch(93% 0.006 268 / .5)">
                    <button type="submit" class="btn-primary-action">Daftarkan</button>
                    <a href="{{ route('petugas.pendaftaran.index') }}" class="btn-ghost-action">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function switchTab(tab) {
    document.getElementById('form-baru').classList.toggle('hidden', tab !== 'baru');
    document.getElementById('form-existing').classList.toggle('hidden', tab !== 'existing');
    document.getElementById('tab-baru').classList.toggle('tab-active', tab === 'baru');
    document.getElementById('tab-existing').classList.toggle('tab-active', tab === 'existing');

    document.querySelectorAll('#form-baru input, #form-baru select, #form-baru textarea').forEach(el => {
        if (tab === 'baru') { el.removeAttribute('tabindex'); el.removeAttribute('disabled'); }
        else { el.setAttribute('tabindex', '-1'); el.setAttribute('disabled', 'disabled'); }
    });
    document.querySelectorAll('#form-existing input, #form-existing select, #form-existing textarea').forEach(el => {
        if (tab === 'existing') { el.removeAttribute('tabindex'); el.removeAttribute('disabled'); }
        else { el.setAttribute('tabindex', '-1'); el.setAttribute('disabled', 'disabled'); }
    });
}

@if(old('id_pasien'))
switchTab('existing');
@endif
</script>
@endsection

@extends('layouts.app')
@section('title', 'Buat Resep')
@section('content')
<div>
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('dokter.pemeriksaan.show', $pemeriksaan->id_pemeriksaan) }}" class="btn btn-ghost btn-circle">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold flex items-center gap-2" style="color:oklch(12% 0.028 262)">
                <span class="material-symbols-outlined">medication</span>
                Buat Resep
            </h1>
            <p class="text-sm" style="color:oklch(20% 0.024 262 / .6)">Tambahkan resep obat untuk pasien</p>
        </div>
    </div>

    <div class="tonal-card mb-8" style="border-color:oklch(93% 0.006 268 / .5)">
        <div class="p-6">
            <h2 class="text-lg font-semibold mb-4" style="color:oklch(12% 0.028 262)">Data Pemeriksaan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center gap-4 p-4 bg-base-200 rounded-box">
                    <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center">
                        <span class="material-symbols-outlined" style="color:oklch(42% 0.086 187)">person</span>
                    </div>
                    <div>
                        <p class="text-xs" style="color:oklch(20% 0.024 262 / .6)">Pasien</p>
                        <p class="font-medium">{{ $pemeriksaan->pendaftaran->pasien->nama_pasien ?? '-' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 p-4 bg-base-200 rounded-box">
                    <div class="w-12 h-12 rounded-full bg-info/10 flex items-center justify-center">
                        <span class="material-symbols-outlined" style="color:oklch(52% 0.1 182)">description</span>
                    </div>
                    <div>
                        <p class="text-xs" style="color:oklch(20% 0.024 262 / .6)">Diagnosa</p>
                        <p class="font-medium">{{ $pemeriksaan->diagnosa ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="tonal-card" style="border-color:oklch(93% 0.006 268 / .5)">
        <div class="p-6">
            <form method="POST" action="{{ route('dokter.resep.store') }}">
                @csrf
                <input type="hidden" name="id_pemeriksaan" value="{{ $pemeriksaan->id_pemeriksaan }}">

                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-lg" style="color:oklch(12% 0.028 262)">Daftar Obat</h3>
                    <button type="button" id="tambah-obat" class="btn-primary-action">
                        <span class="material-symbols-outlined">add</span>
                        Tambah Obat
                    </button>
                </div>

                <div id="obat-container" class="space-y-3">
                    <div class="obat-item p-4" style="border:1px solid oklch(93% 0.006 268 / .5);border-radius:0.75rem;background:oklch(98% 0 0)">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div class="form-control md:col-span-1">
                                <label class="label"><span class="label-text font-medium">Obat <span class="text-error">*</span></span></label>
                                <select name="obat[0][id_obat]" class="select select-bordered" required>
                                    <option value="">-- Pilih Obat --</option>
                                    @foreach($obatList as $o)
                                    <option value="{{ $o->id_obat }}">{{ $o->nama_obat }} (stok: {{ $o->stok }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-control">
                                <label class="label"><span class="label-text font-medium">Jumlah <span class="text-error">*</span></span></label>
                                <input type="number" name="obat[0][jumlah]" class="input input-bordered" min="1" value="1" required>
                            </div>
                            <div class="form-control">
                                <label class="label"><span class="label-text font-medium">Dosis</span></label>
                                <input type="text" name="obat[0][dosis]" class="input input-bordered" placeholder="3x1 sehari">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="btn-group mt-8 pt-6" style="border-top:1px solid oklch(93% 0.006 268 / .5)">
                    <button type="submit" class="btn-primary-action">Simpan Resep</button>
                    <a href="{{ route('dokter.pemeriksaan.index') }}" class="btn-ghost-action">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let obatIndex = 1;
document.getElementById('tambah-obat').addEventListener('click', function() {
    const container = document.getElementById('obat-container');
    const html = `
        <div class="obat-item p-4" style="border:1px solid oklch(93% 0.006 268 / .5);border-radius:0.75rem;background:oklch(98% 0 0)">
            <div class="flex justify-end -mt-1 -mr-1 mb-2">
                <button type="button" class="btn btn-xs btn-circle btn-ghost hapus-obat" style="color:oklch(56% 0.187 22)">
                    <span class="material-symbols-outlined" style="font-size:16px">close</span>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="form-control md:col-span-1">
                    <label class="label"><span class="label-text font-medium">Obat <span class="text-error">*</span></span></label>
                    <select name="obat[${obatIndex}][id_obat]" class="select select-bordered" required>
                        <option value="">-- Pilih Obat --</option>
                        @foreach($obatList as $o)
                        <option value="{{ $o->id_obat }}">{{ $o->nama_obat }} (stok: {{ $o->stok }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-medium">Jumlah <span class="text-error">*</span></span></label>
                    <input type="number" name="obat[${obatIndex}][jumlah]" class="input input-bordered" min="1" value="1" required>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-medium">Dosis</span></label>
                    <input type="text" name="obat[${obatIndex}][dosis]" class="input input-bordered" placeholder="3x1 sehari">
                </div>
            </div>
        </div>`;
    container.insertAdjacentHTML('beforeend', html);
    obatIndex++;
    container.querySelectorAll('.hapus-obat').forEach(btn => {
        btn.addEventListener('click', function() { this.closest('.obat-item').remove(); });
    });
});
</script>
@endsection

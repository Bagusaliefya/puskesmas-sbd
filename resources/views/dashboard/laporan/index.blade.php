@extends('layouts.app')
@section('title', 'Laporan')
@push('styles')
<style>
.chart-container { position: relative; height: 300px; width: 100%; }
</style>
@endpush
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const bulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

    function padData(raw) {
        return bulan.map((_, i) => raw[i + 1] ?? 0);
    }

    const pendaftaranData = padData(@json($pendaftaranPerBulan->pluck('total', 'bulan')));
    const pemeriksaanData = padData(@json($pemeriksaanPerBulan->pluck('total', 'bulan')));
    const obatData = padData(@json($obatTerpakaiPerBulan->pluck('total', 'bulan')));

    new Chart(document.getElementById('chartPendaftaran'), {
        type: 'bar',
        data: {
            labels: bulan,
            datasets: [
                {
                    label: 'Pendaftaran',
                    data: pendaftaranData,
                    backgroundColor: 'oklch(42% 0.086 187 / 0.7)',
                    borderColor: 'oklch(42% 0.086 187)',
                    borderWidth: 2,
                    borderRadius: 4,
                },
                {
                    label: 'Pemeriksaan',
                    data: pemeriksaanData,
                    backgroundColor: 'oklch(52% 0.1 182 / 0.7)',
                    borderColor: 'oklch(52% 0.1 182)',
                    borderWidth: 2,
                    borderRadius: 4,
                },
                {
                    label: 'Obat Terpakai',
                    data: obatData,
                    backgroundColor: 'oklch(30% 0.08 307 / 0.7)',
                    borderColor: 'oklch(30% 0.08 307)',
                    borderWidth: 2,
                    borderRadius: 4,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' },
            },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } },
            },
        },
    });
});
</script>
@endpush
@section('content')
<div class="flex items-center gap-4 mb-8">
    <span class="material-symbols-outlined" style="color:oklch(42% 0.086 187);font-size:2.5rem">assessment</span>
    <div>
        <h1 class="text-2xl font-bold">Laporan</h1>
        <p class="text-sm" style="color:oklch(20% 0.024 262 / .6)">Rekap data operasional Puskesmas</p>
    </div>
</div>

<form method="GET" class="mb-8">
    <div class="tonal-card p-4">
        <div class="flex flex-wrap items-end gap-4">
            <div class="form-control">
                <label class="label"><span class="label-text font-medium">Dari Tanggal</span></label>
                <input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="input input-bordered" style="border-color:oklch(93% 0.006 268 / .5)">
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text font-medium">Sampai Tanggal</span></label>
                <input type="date" name="end_date" value="{{ $endDate ?? '' }}" class="input input-bordered" style="border-color:oklch(93% 0.006 268 / .5)">
            </div>
            <button type="submit" class="btn-primary-action">
                <span class="material-symbols-outlined">filter_alt</span> Filter
            </button>
            @if($startDate || $endDate)
            <a href="{{ route('laporan.index') }}" class="btn-ghost-action">
                <span class="material-symbols-outlined">close</span> Reset
            </a>
            @endif
            <div class="flex gap-2 ml-auto">
                <a href="{{ route('laporan.pdf', request()->query()) }}" class="btn-secondary-action">
                    <span class="material-symbols-outlined">picture_as_pdf</span> PDF
                </a>
                <a href="{{ route('laporan.excel', request()->query()) }}" class="btn-secondary-action">
                    <span class="material-symbols-outlined">table_chart</span> Excel
                </a>
            </div>
        </div>
    </div>
</form>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="tonal-card p-6">
        <div class="flex items-center gap-4">
            <span class="material-symbols-outlined" style="color:oklch(42% 0.086 187);font-size:2.5rem">assignment</span>
            <div>
                <div style="color:oklch(20% 0.024 262 / .6);font-size:.875rem">Total Pendaftaran</div>
                <div class="text-3xl font-extrabold" style="color:oklch(42% 0.086 187)">{{ $totalPendaftaran }}</div>
            </div>
        </div>
    </div>
    <div class="tonal-card p-6">
        <div class="flex items-center gap-4">
            <span class="material-symbols-outlined" style="color:oklch(52% 0.1 182);font-size:2.5rem">stethoscope</span>
            <div>
                <div style="color:oklch(20% 0.024 262 / .6);font-size:.875rem">Total Pemeriksaan</div>
                <div class="text-3xl font-extrabold" style="color:oklch(52% 0.1 182)">{{ $totalPemeriksaan }}</div>
            </div>
        </div>
    </div>
    <div class="tonal-card p-6">
        <div class="flex items-center gap-4">
            <span class="material-symbols-outlined" style="color:oklch(30% 0.08 307);font-size:2.5rem">medication</span>
            <div>
                <div style="color:oklch(20% 0.024 262 / .6);font-size:.875rem">Total Obat Terpakai</div>
                <div class="text-3xl font-extrabold" style="color:oklch(30% 0.08 307)">{{ $totalObatTerpakai }}</div>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="tonal-card p-6" style="border:1px solid oklch(93% 0.006 268 / .5)">
        <h2 class="text-lg font-semibold flex items-center gap-2 mb-4">
            <span class="material-symbols-outlined" style="color:oklch(42% 0.086 187);font-size:1.5rem">bar_chart</span>
            Grafik Pendaftaran, Pemeriksaan & Obat ({{ date('Y') }})
        </h2>
        <div class="chart-container">
            <canvas id="chartPendaftaran"></canvas>
        </div>
    </div>

    <div class="tonal-card p-6" style="border:1px solid oklch(93% 0.006 268 / .5)">
        <h2 class="text-lg font-semibold flex items-center gap-2 mb-4">
            <span class="material-symbols-outlined" style="color:oklch(42% 0.086 187);font-size:1.5rem">table_rows</span>
            Pendaftaran per Bulan ({{ date('Y') }})
        </h2>
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead><tr><th>Bulan</th><th>Jumlah</th></tr></thead>
                <tbody>
                    @php $bulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des']; @endphp
                    @forelse($pendaftaranPerBulan as $p)
                    <tr><td>{{ $bulan[$p->bulan - 1] ?? $p->bulan }}</td><td><span class="badge badge-primary badge-sm">{{ $p->total }}</span></td></tr>
                    @empty
                    <tr><td colspan="2" class="text-center py-4" style="color:oklch(20% 0.024 262 / .6)">Belum ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

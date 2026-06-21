@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
@php $user = auth()->user(); @endphp

{{-- HERO --}}
<section class="mb-10 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold hero-title" style="color:oklch(42% 0.086 187)">Selamat Datang, {{ $user->name }}</h2>
        <p class="text-sm mt-1 metric-label" style="color:oklch(20% 0.024 262 / .6)">
            @if($user->role === 'admin')
                Kelola operasional Puskesmas dengan mudah dan efisien.
            @elseif($user->role === 'petugas')
                Kelola pendaftaran dan data pasien harian.
            @else
                Periksa pasien dan kelola resep obat.
            @endif
        </p>
    </div>
    <div class="hidden md:flex items-center gap-3 p-4 rounded-2xl hero-badge" style="background:oklch(42% 0.086 187 / .05);border:1px solid oklch(42% 0.086 187 / .1)">
        <div class="text-right">
            <p class="font-semibold text-sm hero-title" style="color:oklch(42% 0.086 187)">{{ config('app.name') }}</p>
            <p class="text-xs italic metric-label" style="color:oklch(20% 0.024 262 / .6)">"Melayani dengan Hati"</p>
        </div>
        <div class="w-12 h-12 rounded-full flex items-center justify-center hero-icon-bg" style="background:oklch(100% 0 0);box-shadow:0 2px 8px oklch(42% 0.086 187 / .1)">
            <span class="material-symbols-outlined text-3xl hero-title" style="color:oklch(42% 0.086 187)">health_metrics</span>
        </div>
    </div>
</section>

{{-- ADMIN DASHBOARD --}}
@if($user->role === 'admin')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <div class="tonal-card p-6 flex items-center gap-5">
        <div class="w-14 h-14 rounded-full flex items-center justify-center" style="background:oklch(42% 0.086 187 / .1);color:oklch(42% 0.086 187)">
            <span class="material-symbols-outlined text-3xl">badge</span>
        </div>
        <div>
            <p class="text-xs font-medium metric-label" style="color:oklch(20% 0.024 262 / .6)">Total Pegawai</p>
            <p class="text-3xl font-extrabold metric-value" style="color:oklch(12% 0.028 262)">{{ $data['total_pegawai'] }}</p>
        </div>
    </div>
    <div class="tonal-card p-6 flex items-center gap-5">
        <div class="w-14 h-14 rounded-full flex items-center justify-center" style="background:oklch(52% 0.1 182 / .1);color:oklch(52% 0.1 182)">
            <span class="material-symbols-outlined text-3xl">patient_list</span>
        </div>
        <div>
            <p class="text-xs font-medium metric-label" style="color:oklch(20% 0.024 262 / .6)">Total Pasien</p>
            <p class="text-3xl font-extrabold metric-value" style="color:oklch(12% 0.028 262)">{{ $data['total_pasien'] }}</p>
        </div>
    </div>
    <div class="tonal-card p-6 flex items-center gap-5">
        <div class="w-14 h-14 rounded-full flex items-center justify-center" style="background:oklch(63% 0.106 210 / .1);color:oklch(63% 0.106 210)">
            <span class="material-symbols-outlined text-3xl">pill</span>
        </div>
        <div>
            <p class="text-xs font-medium metric-label" style="color:oklch(20% 0.024 262 / .6)">Total Obat</p>
            <p class="text-3xl font-extrabold metric-value" style="color:oklch(12% 0.028 262)">{{ $data['total_obat'] }}</p>
        </div>
    </div>
    <div class="tonal-card p-6 flex items-center gap-5">
        <div class="w-14 h-14 rounded-full flex items-center justify-center" style="background:oklch(42% 0.086 187 / .1);color:oklch(42% 0.086 187)">
            <span class="material-symbols-outlined text-3xl">event_note</span>
        </div>
        <div>
            <p class="text-xs font-medium metric-label" style="color:oklch(20% 0.024 262 / .6)">Total Pendaftaran</p>
            <p class="text-3xl font-extrabold metric-value" style="color:oklch(12% 0.028 262)">{{ $data['total_pendaftaran'] }}</p>
        </div>
    </div>
</div>

@if(count($data['obat_menipis']) > 0)
<div class="tonal-card p-4 mb-6" style="border-color:oklch(58% 0.112 28 / 0.3);background:oklch(58% 0.112 28 / 0.05)">
    <div class="flex items-center gap-3 flex-wrap">
        <span class="material-symbols-outlined" style="color:oklch(58% 0.112 28);font-size:2rem">inventory_2</span>
        <div class="flex-1">
            <p class="font-medium" style="color:oklch(58% 0.112 28)">Stok Obat Menipis</p>
            <p class="text-sm" style="color:oklch(20% 0.024 262 / .6)">
                @foreach($data['obat_menipis'] as $i => $o)
                    <span class="inline-flex items-center gap-1 mr-3"><span class="material-symbols-outlined" style="font-size:14px">medication</span> {{ $o->nama_obat }} (stok: {{ $o->stok }}/{{ $o->stok_minimum }})</span>
                @endforeach
            </p>
        </div>
        <a href="{{ route('obat.index') }}" class="btn-primary-action text-sm">
            <span class="material-symbols-outlined">visibility</span> Lihat
        </a>
    </div>
</div>
@endif

<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 lg:col-span-8 tonal-card overflow-hidden">
        <div class="p-6 border-b flex items-center justify-between" style="border-color:oklch(93% 0.006 268 / .5)">
            <h3 class="font-bold flex items-center gap-2 card-header" style="color:oklch(42% 0.086 187)">
                <span class="material-symbols-outlined">bolt</span> Quick Actions
            </h3>
            <span class="text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full badge-section" style="background:oklch(97.5% 0.006 265);color:oklch(20% 0.024 262 / .6)">Primary Operations</span>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-5 p-6">
            <a href="{{ route('pegawai.index') }}" class="group flex flex-col items-center gap-3 p-5 rounded-2xl border-2 border-dashed transition-all quick-action-btn" style="border-color:oklch(93% 0.006 268)">
                <div class="w-14 h-14 rounded-full flex items-center justify-center transition-all shadow-sm group-hover:shadow-md" style="background:oklch(100% 0 0);color:oklch(42% 0.086 187)">
                    <span class="material-symbols-outlined text-2xl">badge</span>
                </div>
                <span class="text-sm font-semibold metric-value" style="color:oklch(12% 0.028 262)">Kelola Pegawai</span>
            </a>
            <a href="{{ route('pasien.index') }}" class="group flex flex-col items-center gap-3 p-5 rounded-2xl border-2 border-dashed transition-all quick-action-btn" style="border-color:oklch(93% 0.006 268)">
                <div class="w-14 h-14 rounded-full flex items-center justify-center transition-all shadow-sm group-hover:shadow-md" style="background:oklch(100% 0 0);color:oklch(52% 0.1 182)">
                    <span class="material-symbols-outlined text-2xl">personal_injury</span>
                </div>
                <span class="text-sm font-semibold metric-value" style="color:oklch(12% 0.028 262)">Kelola Pasien</span>
            </a>
            <a href="{{ route('obat.index') }}" class="group flex flex-col items-center gap-3 p-5 rounded-2xl border-2 border-dashed transition-all quick-action-btn" style="border-color:oklch(93% 0.006 268)">
                <div class="w-14 h-14 rounded-full flex items-center justify-center transition-all shadow-sm group-hover:shadow-md" style="background:oklch(100% 0 0);color:oklch(63% 0.106 210)">
                    <span class="material-symbols-outlined text-2xl">vaccines</span>
                </div>
                <span class="text-sm font-semibold metric-value" style="color:oklch(12% 0.028 262)">Kelola Obat</span>
            </a>
            <a href="{{ route('laporan.index') }}" class="group flex flex-col items-center gap-3 p-5 rounded-2xl border-2 border-dashed transition-all quick-action-btn" style="border-color:oklch(93% 0.006 268)">
                <div class="w-14 h-14 rounded-full flex items-center justify-center transition-all shadow-sm group-hover:shadow-md" style="background:oklch(100% 0 0);color:oklch(42% 0.086 187)">
                    <span class="material-symbols-outlined text-2xl">insights</span>
                </div>
                <span class="text-sm font-semibold metric-value" style="color:oklch(12% 0.028 262)">Lihat Laporan</span>
            </a>
            <a href="{{ route('user.index') }}" class="group flex flex-col items-center gap-3 p-5 rounded-2xl border-2 border-dashed transition-all quick-action-btn" style="border-color:oklch(93% 0.006 268)">
                <div class="w-14 h-14 rounded-full flex items-center justify-center transition-all shadow-sm group-hover:shadow-md" style="background:oklch(100% 0 0);color:oklch(72% 0.166 70)">
                    <span class="material-symbols-outlined text-2xl">manage_accounts</span>
                </div>
                <span class="text-sm font-semibold metric-value" style="color:oklch(12% 0.028 262)">Manajemen User</span>
            </a>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-6 tonal-card overflow-hidden">
        <div class="p-6 border-b flex items-center gap-2" style="border-color:oklch(93% 0.006 268 / .5)">
            <span class="material-symbols-outlined" style="color:oklch(52% 0.1 182)">badge</span>
            <h3 class="font-bold card-header">Data Petugas</h3>
        </div>
        <div class="table-wrap">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Loket</th>
                        <th>No. HP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data['petugas'] as $p)
                    <tr>
                        <td class="font-medium">{{ $p->pegawai->nama_pegawai ?? '-' }}</td>
                        <td>{{ $p->loket ?? '-' }}</td>
                        <td>{{ $p->pegawai->no_hp ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-6 empty-state" style="color:oklch(20% 0.024 262 / .5)">
                            <span class="material-symbols-outlined text-3xl">info</span>
                            <p class="text-sm mt-1">Belum ada data petugas</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-6 tonal-card overflow-hidden">
        <div class="p-6 border-b flex items-center gap-2" style="border-color:oklch(93% 0.006 268 / .5)">
            <span class="material-symbols-outlined" style="color:oklch(42% 0.086 187)">stethoscope</span>
            <h3 class="font-bold card-header">Data Dokter</h3>
        </div>
        <div class="table-wrap">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Spesialisasi</th>
                        <th>No. HP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data['dokter'] as $d)
                    <tr>
                        <td class="font-medium">{{ $d->pegawai->nama_pegawai ?? '-' }}</td>
                        <td>{{ $d->spesialisasi ?? '-' }}</td>
                        <td>{{ $d->pegawai->no_hp ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-6 empty-state" style="color:oklch(20% 0.024 262 / .5)">
                            <span class="material-symbols-outlined text-3xl">info</span>
                            <p class="text-sm mt-1">Belum ada data dokter</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- PETUGAS DASHBOARD --}}
@elseif($user->role === 'petugas')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
    <div class="tonal-card p-6 flex items-center gap-5">
        <div class="w-14 h-14 rounded-full flex items-center justify-center" style="background:oklch(52% 0.1 182 / .1);color:oklch(52% 0.1 182)">
            <span class="material-symbols-outlined text-3xl">patient_list</span>
        </div>
        <div>
            <p class="text-xs font-medium metric-label" style="color:oklch(20% 0.024 262 / .6)">Total Pasien</p>
            <p class="text-3xl font-extrabold metric-value" style="color:oklch(12% 0.028 262)">{{ $data['total_pasien'] }}</p>
        </div>
    </div>
    <div class="tonal-card p-6 flex items-center gap-5">
        <div class="w-14 h-14 rounded-full flex items-center justify-center" style="background:oklch(72% 0.166 70 / .1);color:oklch(72% 0.166 70)">
            <span class="material-symbols-outlined text-3xl">how_to_reg</span>
        </div>
        <div>
            <p class="text-xs font-medium metric-label" style="color:oklch(20% 0.024 262 / .6)">Pendaftaran Hari Ini</p>
            <p class="text-3xl font-extrabold metric-value" style="color:oklch(12% 0.028 262)">{{ $data['total_pendaftaran_hari_ini'] }}</p>
        </div>
    </div>
</div>

@if(isset($data['antrean']) && $data['antrean']->count() > 0)
<div class="tonal-card mb-8 overflow-hidden">
    <div class="p-6 border-b flex items-center gap-2" style="border-color:oklch(93% 0.006 268 / .5)">
        <span class="material-symbols-outlined" style="color:oklch(72% 0.166 70)">pending_actions</span>
        <h3 class="font-bold card-header">Antrean Pendaftaran Hari Ini</h3>
    </div>
    <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($data['antrean'] as $i => $a)
        <div class="rounded-2xl border-2 p-5 flex flex-col gap-3 transition-all hover:shadow-md" style="border-color:{{ $i === 0 ? 'oklch(42% 0.086 187 / .4)' : 'oklch(93% 0.006 268)' }};{{ $i === 0 ? 'background:oklch(42% 0.086 187 / .04)' : '' }}">
            <div class="flex items-center justify-between">
                <span class="font-bold text-lg px-3 py-1 rounded-full" style="background:{{ $i === 0 ? 'oklch(42% 0.086 187)' : 'oklch(93% 0.006 268)' }};color:{{ $i === 0 ? '#fff' : 'oklch(20% 0.024 262 / .6)' }}">
                    Antrian #{{ $i + 1 }}
                </span>
                @if($i === 0)
                <span class="text-xs font-semibold px-2 py-1 rounded-full" style="background:oklch(72% 0.166 70 / .15);color:oklch(72% 0.166 70)">
                    <span class="material-symbols-outlined text-sm align-middle">double_arrow</span> Sekarang
                </span>
                @endif
            </div>
            <div>
                <p class="font-semibold text-base" style="color:oklch(12% 0.028 262)">{{ $a->pasien->nama_pasien ?? '-' }}</p>
                @php $keluhanLines = explode("\n- ", $a->keluhan); @endphp
                <div class="text-xs mt-1 leading-relaxed" style="color:oklch(20% 0.024 262 / .5)">
                    @foreach($keluhanLines as $kl)
                        <span>{{ $loop->first ? '' : '• ' }}{{ $kl }}</span><br>
                    @endforeach
                </div>
            </div>
            <div class="flex items-center gap-2 text-xs" style="color:oklch(20% 0.024 262 / .5)">
                <span class="material-symbols-outlined text-sm">schedule</span>
                {{ \Carbon\Carbon::parse($a->created_at)->format('H:i') }} WIB
                @if(substr_count($a->keluhan, "\n") > 0)
                <span class="px-2 py-0.5 rounded-full text-xs font-semibold" style="background:oklch(63% 0.106 210 / .12);color:oklch(63% 0.106 210)">Rangkap</span>
                @endif
                <span class="px-2 py-0.5 rounded-full text-xs" style="background:oklch(93% 0.006 268)">{{ $a->tipe_pendaftaran === 'mandiri' ? 'Mandiri' : 'Petugas' }}</span>
            </div>
        </div>
        @endforeach
    </div>
</div>
@else
<div class="tonal-card p-10 text-center mb-8 empty-state" style="color:oklch(20% 0.024 262 / .5)">
    <span class="material-symbols-outlined text-5xl">info</span>
    <p class="mt-3 text-sm">Belum ada antrean pendaftaran hari ini.</p>
</div>
@endif

<div class="flex flex-wrap gap-4">
    <a href="{{ route('pasien.create') }}" class="btn-primary-action">
        <span class="material-symbols-outlined text-lg">person_add</span> Tambah Pasien
    </a>
    <a href="{{ route('petugas.pendaftaran.create') }}" class="btn-primary-action">
        <span class="material-symbols-outlined text-lg">app_registration</span> Daftarkan Pasien
    </a>
</div>

{{-- DOKTER DASHBOARD --}}
@elseif($user->role === 'dokter')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
    <div class="tonal-card p-6 flex items-center gap-5">
        <div class="w-14 h-14 rounded-full flex items-center justify-center" style="background:oklch(42% 0.086 187 / .1);color:oklch(42% 0.086 187)">
            <span class="material-symbols-outlined text-3xl">stethoscope</span>
        </div>
        <div>
            <p class="text-xs font-medium metric-label" style="color:oklch(20% 0.024 262 / .6)">Pemeriksaan Hari Ini</p>
            <p class="text-3xl font-extrabold metric-value" style="color:oklch(12% 0.028 262)">{{ $data['total_pemeriksaan_hari_ini'] }}</p>
            <p class="text-xs mt-2" style="color:oklch(20% 0.024 262 / .6)">
                @if($data['total_menunggu'] > 0)
                    <span style="color:oklch(72% 0.166 70);font-weight:600">{{ $data['total_menunggu'] }} pasien menunggu</span>
                @else
                    Tidak ada antrean
                @endif
            </p>
        </div>
    </div>
    <div class="tonal-card p-6 flex items-center gap-5">
        <div class="w-14 h-14 rounded-full flex items-center justify-center" style="background:oklch(72% 0.166 70 / .1);color:oklch(72% 0.166 70)">
            <span class="material-symbols-outlined text-3xl">pending_actions</span>
        </div>
        <div>
            <p class="text-xs font-medium metric-label" style="color:oklch(20% 0.024 262 / .6)">Menunggu Periksa</p>
            <p class="text-3xl font-extrabold metric-value" style="color:oklch(12% 0.028 262)">{{ $data['total_menunggu'] ?? 0 }}</p>
        </div>
    </div>
</div>

@if(isset($data['daftar_periksa']) && $data['daftar_periksa']->count() > 0)
<div class="tonal-card mb-8 overflow-hidden">
    <div class="p-6 border-b flex items-center gap-2" style="border-color:oklch(93% 0.006 268 / .5)">
        <span class="material-symbols-outlined" style="color:oklch(42% 0.086 187)">patient_list</span>
        <h3 class="font-bold card-header">Daftar Pasien Menunggu Periksa</h3>
    </div>
    <div class="table-wrap">
        <table class="table w-full">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pasien</th>
                    <th>Keluhan</th>
                    <th>Sumber</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['daftar_periksa'] as $i => $p)
                <tr @if($p->dipanggil_at) style="background:oklch(42% 0.086 187 / .03)" @endif>
                    <td>
                        @if($p->dipanggil_at)
                        <span class="badge badge-info badge-sm">
                            <span class="material-symbols-outlined text-xs align-middle">campaign</span>
                            {{ $p->dipanggil_at->format('H:i') }}
                        </span>
                        @else
                        {{ $i + 1 }}
                        @endif
                    </td>
                    <td class="font-medium">{{ $p->pasien->nama_pasien ?? '-' }}</td>
                    <td>{{ Str::limit($p->keluhan, 40) ?? '-' }}</td>
                    <td>
                        @if($p->tipe_pendaftaran === 'mandiri')
                            <span class="badge badge-accent badge-sm">Mandiri</span>
                        @else
                            <span class="badge badge-ghost badge-sm">Petugas</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('dokter.pemeriksaan.create', $p->id_pendaftaran) }}" class="btn-primary-action btn-mobile-full">
                            <span class="material-symbols-outlined text-sm">stethoscope</span> Periksa
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<div class="tonal-card p-10 text-center mb-8 empty-state" style="color:oklch(20% 0.024 262 / .5)">
    <span class="material-symbols-outlined text-5xl">info</span>
    <p class="mt-3 text-sm">Belum ada pasien yang terdaftar untuk diperiksa hari ini.</p>
</div>
@endif

<div class="flex gap-4">
    <a href="{{ route('dokter.pemeriksaan.index') }}" class="btn-primary-action">
        <span class="material-symbols-outlined text-lg">history</span> Riwayat Pemeriksaan
    </a>
</div>
@endif
@endsection

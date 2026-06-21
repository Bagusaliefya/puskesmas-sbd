<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Puskesmas</title>
    <style>
        body { font-family: sans-serif; font-size: 12pt; color: #333; padding: 40px; }
        h1 { text-align: center; font-size: 18pt; margin-bottom: 5px; }
        .subtitle { text-align: center; color: #666; font-size: 10pt; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #ddd; padding: 8px 12px; text-align: left; }
        th { background: #f5f5f5; font-weight: bold; }
        .text-right { text-align: right; }
        .footer { text-align: center; color: #999; font-size: 9pt; margin-top: 40px; border-top: 1px solid #eee; padding-top: 10px; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>{{ config('app.name') }}</h1>
    <p class="subtitle">
        Laporan Operasional
        @if($startDate || $endDate)
            ({{ $startDate ?? 'awal' }} - {{ $endDate ?? 'sekarang' }})
        @else
            Tahun {{ date('Y') }}
        @endif
    </p>

    <table>
        <tr><th style="width:60%">Metrik</th><th style="width:40%">Nilai</th></tr>
        <tr><td>Total Pendaftaran</td><td>{{ $totalPendaftaran }}</td></tr>
        <tr><td>Total Pemeriksaan</td><td>{{ $totalPemeriksaan }}</td></tr>
        <tr><td>Total Obat Terpakai</td><td>{{ $totalObatTerpakai }}</td></tr>
    </table>

    <h2 style="font-size:13pt">Pendaftaran per Bulan</h2>
    <table>
        <thead><tr><th>Bulan</th><th>Jumlah</th></tr></thead>
        <tbody>
            @php $bulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des']; @endphp
            @forelse($pendaftaranPerBulan as $p)
            <tr><td>{{ $bulan[$p->bulan - 1] ?? $p->bulan }}</td><td>{{ $p->total }}</td></tr>
            @empty
            <tr><td colspan="2" style="text-align:center;color:#999">Belum ada data</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada {{ date('d M Y H:i') }} — {{ config('app.name') }}
    </div>
</body>
</html>

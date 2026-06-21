<?php

namespace App\Http\Controllers\Api;

use App\Models\DetailResep;
use App\Models\Pemeriksaan;
use App\Models\Pendaftaran;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LaporanController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $pendaftaranQuery = Pendaftaran::query();
        $pemeriksaanQuery = Pemeriksaan::query();
        $detailResepQuery = DetailResep::query();

        if ($startDate) {
            $pendaftaranQuery->whereDate('tanggal_daftar', '>=', $startDate);
            $pemeriksaanQuery->whereDate('tanggal_periksa', '>=', $startDate);
            $detailResepQuery->whereHas('resep.pemeriksaan', fn($q) => $q->whereDate('tanggal_periksa', '>=', $startDate));
        }
        if ($endDate) {
            $pendaftaranQuery->whereDate('tanggal_daftar', '<=', $endDate);
            $pemeriksaanQuery->whereDate('tanggal_periksa', '<=', $endDate);
            $detailResepQuery->whereHas('resep.pemeriksaan', fn($q) => $q->whereDate('tanggal_periksa', '<=', $endDate));
        }

        $pendaftaranPerBulan = Pendaftaran::selectRaw('MONTH(tanggal_daftar) as bulan, COUNT(*) as total')
            ->when($startDate, fn($q) => $q->whereDate('tanggal_daftar', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('tanggal_daftar', '<=', $endDate))
            ->groupBy('bulan')->orderBy('bulan')->get();

        $pemeriksaanPerBulan = Pemeriksaan::selectRaw('MONTH(tanggal_periksa) as bulan, COUNT(*) as total')
            ->when($startDate, fn($q) => $q->whereDate('tanggal_periksa', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('tanggal_periksa', '<=', $endDate))
            ->groupBy('bulan')->orderBy('bulan')->get();

        $obatTerpakaiPerBulan = DetailResep::selectRaw('MONTH(pemeriksaan.tanggal_periksa) as bulan, SUM(detail_resep.jumlah) as total')
            ->join('resep', 'detail_resep.id_resep', '=', 'resep.id_resep')
            ->join('pemeriksaan', 'resep.id_pemeriksaan', '=', 'pemeriksaan.id_pemeriksaan')
            ->when($startDate, fn($q) => $q->whereDate('pemeriksaan.tanggal_periksa', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('pemeriksaan.tanggal_periksa', '<=', $endDate))
            ->groupBy('bulan')->orderBy('bulan')->get();

        $bulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

        return $this->success([
            'ringkasan' => [
                'total_pendaftaran' => (clone $pendaftaranQuery)->count(),
                'total_pemeriksaan' => (clone $pemeriksaanQuery)->count(),
                'total_obat_terpakai' => (clone $detailResepQuery)->sum('jumlah'),
            ],
            'grafik' => [
                'bulan' => $bulan,
                'pendaftaran' => array_map(fn($b) => (int) ($pendaftaranPerBulan->firstWhere('bulan', $b)?->total ?? 0), range(1, 12)),
                'pemeriksaan' => array_map(fn($b) => (int) ($pemeriksaanPerBulan->firstWhere('bulan', $b)?->total ?? 0), range(1, 12)),
                'obat_terpakai' => array_map(fn($b) => (int) ($obatTerpakaiPerBulan->firstWhere('bulan', $b)?->total ?? 0), range(1, 12)),
            ],
        ]);
    }
}

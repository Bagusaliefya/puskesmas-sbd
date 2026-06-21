<?php

namespace App\Http\Controllers;

use App\Models\DetailResep;
use App\Models\Pemeriksaan;
use App\Models\Pendaftaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $pendaftaranQuery = Pendaftaran::query();
        $pemeriksaanQuery = Pemeriksaan::query();
        $detailResepQuery = DetailResep::query();

        if ($startDate) {
            $pendaftaranQuery->whereDate('tanggal_daftar', '>=', $startDate);
            $pemeriksaanQuery->whereDate('tanggal_periksa', '>=', $startDate);
            $detailResepQuery->whereHas('resep.pemeriksaan', function ($q) use ($startDate) {
                $q->whereDate('tanggal_periksa', '>=', $startDate);
            });
        }

        if ($endDate) {
            $pendaftaranQuery->whereDate('tanggal_daftar', '<=', $endDate);
            $pemeriksaanQuery->whereDate('tanggal_periksa', '<=', $endDate);
            $detailResepQuery->whereHas('resep.pemeriksaan', function ($q) use ($endDate) {
                $q->whereDate('tanggal_periksa', '<=', $endDate);
            });
        }

        if ($startDate && $endDate && $startDate > $endDate) {
            return back()->with('error', 'Tanggal awal tidak boleh melebihi tanggal akhir.');
        }

        $totalPendaftaran = $pendaftaranQuery->count();
        $totalPemeriksaan = $pemeriksaanQuery->count();

        $pendaftaranPerBulan = Pendaftaran::selectRaw('MONTH(tanggal_daftar) as bulan, COUNT(*) as total')
            ->when($startDate, fn($q) => $q->whereDate('tanggal_daftar', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('tanggal_daftar', '<=', $endDate))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $totalObatTerpakai = (clone $detailResepQuery)->sum('jumlah');

        $pemeriksaanPerBulan = Pemeriksaan::selectRaw('MONTH(tanggal_periksa) as bulan, COUNT(*) as total')
            ->when($startDate, fn($q) => $q->whereDate('tanggal_periksa', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('tanggal_periksa', '<=', $endDate))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $obatTerpakaiPerBulan = DetailResep::selectRaw('MONTH(pemeriksaan.tanggal_periksa) as bulan, SUM(detail_resep.jumlah) as total')
            ->join('resep', 'detail_resep.id_resep', '=', 'resep.id_resep')
            ->join('pemeriksaan', 'resep.id_pemeriksaan', '=', 'pemeriksaan.id_pemeriksaan')
            ->when($startDate, fn($q) => $q->whereDate('pemeriksaan.tanggal_periksa', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('pemeriksaan.tanggal_periksa', '<=', $endDate))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        return view('dashboard.laporan.index', compact(
            'totalPendaftaran', 'totalPemeriksaan',
            'pendaftaranPerBulan', 'totalObatTerpakai',
            'pemeriksaanPerBulan', 'obatTerpakaiPerBulan',
            'startDate', 'endDate'
        ));
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $pendaftaranQuery = Pendaftaran::query();
        $pemeriksaanQuery = Pemeriksaan::query();
        $detailResepQuery = DetailResep::query();

        if ($startDate) {
            $pendaftaranQuery->whereDate('tanggal_daftar', '>=', $startDate);
            $pemeriksaanQuery->whereDate('tanggal_periksa', '>=', $startDate);
            $detailResepQuery->whereHas('resep.pemeriksaan', function ($q) use ($startDate) {
                $q->whereDate('tanggal_periksa', '>=', $startDate);
            });
        }

        if ($endDate) {
            $pendaftaranQuery->whereDate('tanggal_daftar', '<=', $endDate);
            $pemeriksaanQuery->whereDate('tanggal_periksa', '<=', $endDate);
            $detailResepQuery->whereHas('resep.pemeriksaan', function ($q) use ($endDate) {
                $q->whereDate('tanggal_periksa', '<=', $endDate);
            });
        }

        $totalPendaftaran = $pendaftaranQuery->count();
        $totalPemeriksaan = $pemeriksaanQuery->count();
        $totalObatTerpakai = (clone $detailResepQuery)->sum('jumlah');

        $pendaftaranPerBulan = Pendaftaran::selectRaw('MONTH(tanggal_daftar) as bulan, COUNT(*) as total')
            ->when($startDate, fn($q) => $q->whereDate('tanggal_daftar', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('tanggal_daftar', '<=', $endDate))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $pdf = Pdf::loadView('dashboard.laporan.pdf', compact(
            'totalPendaftaran', 'totalPemeriksaan', 'totalObatTerpakai',
            'pendaftaranPerBulan', 'startDate', 'endDate'
        ));

        $filename = 'laporan-puskesmas-' . date('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }

    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        return Excel::download(
            new LaporanExport($startDate, $endDate),
            'laporan-puskesmas-' . date('Y-m-d') . '.xlsx'
        );
    }
}

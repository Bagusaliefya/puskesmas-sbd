<?php

namespace App\Exports;

use App\Models\DetailResep;
use App\Models\Pemeriksaan;
use App\Models\Pendaftaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class LaporanExport implements FromCollection, WithHeadings, WithTitle
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $pendaftaranQuery = Pendaftaran::query();
        $pemeriksaanQuery = Pemeriksaan::query();
        $detailResepQuery = DetailResep::query();

        if ($this->startDate) {
            $pendaftaranQuery->whereDate('tanggal_daftar', '>=', $this->startDate);
            $pemeriksaanQuery->whereDate('tanggal_periksa', '>=', $this->startDate);
            $detailResepQuery->whereHas('resep.pemeriksaan', function ($q) {
                $q->whereDate('tanggal_periksa', '>=', $this->startDate);
            });
        }

        if ($this->endDate) {
            $pendaftaranQuery->whereDate('tanggal_daftar', '<=', $this->endDate);
            $pemeriksaanQuery->whereDate('tanggal_periksa', '<=', $this->endDate);
            $detailResepQuery->whereHas('resep.pemeriksaan', function ($q) {
                $q->whereDate('tanggal_periksa', '<=', $this->endDate);
            });
        }

        $totalPendaftaran = $pendaftaranQuery->count();
        $totalPemeriksaan = $pemeriksaanQuery->count();
        $totalObatTerpakai = (clone $detailResepQuery)->sum('jumlah');

        return collect([
            ['Metrik', 'Nilai'],
            ['Total Pendaftaran', $totalPendaftaran],
            ['Total Pemeriksaan', $totalPemeriksaan],
            ['Total Obat Terpakai', $totalObatTerpakai],
        ]);
    }

    public function headings(): array
    {
        $title = 'Laporan Puskesmas';
        if ($this->startDate || $this->endDate) {
            $title .= ' (' . ($this->startDate ?? 'awal') . ' - ' . ($this->endDate ?? 'sekarang') . ')';
        }
        return [[$title], ['']];
    }

    public function title(): string
    {
        return 'Ringkasan';
    }
}

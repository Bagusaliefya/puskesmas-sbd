<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Pegawai;
use App\Models\Pemeriksaan;
use App\Models\Pendaftaran;
use App\Models\Petugas;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $data = match ($user->role) {
            'admin' => [
                'total_pegawai' => Pegawai::count(),
                'total_pasien' => Pasien::count(),
                'total_obat' => Obat::count(),
                'total_pendaftaran' => Pendaftaran::count(),
                'obat_menipis' => Obat::hampirHabis()->get(),
                'petugas' => Petugas::with('pegawai')->get(),
                'dokter' => Dokter::with('pegawai')->get(),
            ],
            'petugas' => [
                'total_pasien' => Pasien::count(),
                'total_pendaftaran_hari_ini' => Pendaftaran::whereDate('tanggal_daftar', today())->count(),
                'antrean' => Pendaftaran::whereDate('tanggal_daftar', today())
                    ->doesntHave('pemeriksaan')
                    ->with('pasien')
                    ->orderBy('created_at')
                    ->get(),
            ],
            'dokter' => [
                'total_pemeriksaan_hari_ini' => Pemeriksaan::whereDate('tanggal_periksa', today())->count(),
                'total_menunggu' => Pendaftaran::whereDate('tanggal_daftar', today())
                    ->whereNotNull('dipanggil_at')
                    ->doesntHave('pemeriksaan')
                    ->count(),
                'daftar_periksa' => Pendaftaran::whereDate('tanggal_daftar', today())
                    ->whereNotNull('dipanggil_at')
                    ->doesntHave('pemeriksaan')
                    ->with('pasien')
                    ->orderBy('dipanggil_at')
                    ->get(),
            ],
            default => [],
        };

        $status = statusPuskesmas();

        return view('dashboard.index', compact('user', 'data', 'status'));
    }

    public function search()
    {
        $q = request('q');

        if (! $q) {
            return redirect()->route('dashboard');
        }

        $pasien = Pasien::where('nama_pasien', 'like', "%{$q}%")
            ->orWhere('no_hp', 'like', "%{$q}%")
            ->limit(20)
            ->get();

        $obat = Obat::where('nama_obat', 'like', "%{$q}%")
            ->limit(20)
            ->get();

        $pegawai = Pegawai::where('nama_pegawai', 'like', "%{$q}%")
            ->orWhere('jabatan', 'like', "%{$q}%")
            ->limit(20)
            ->get();

        return view('dashboard.search', compact('q', 'pasien', 'obat', 'pegawai'));
    }
}

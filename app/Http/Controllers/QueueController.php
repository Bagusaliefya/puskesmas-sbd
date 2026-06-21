<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Illuminate\Http\JsonResponse;

class QueueController extends Controller
{
    public function display()
    {
        $sedangDipanggil = Pendaftaran::whereDate('tanggal_daftar', today())
            ->whereNotNull('dipanggil_at')
            ->doesntHave('pemeriksaan')
            ->with('pasien')
            ->latest('dipanggil_at')
            ->first();

        $antrean = Pendaftaran::whereDate('tanggal_daftar', today())
            ->doesntHave('pemeriksaan')
            ->whereNull('dipanggil_at')
            ->with('pasien')
            ->orderBy('created_at')
            ->get();

        $selesai = Pendaftaran::whereDate('tanggal_daftar', today())
            ->whereHas('pemeriksaan')
            ->with('pasien')
            ->latest('updated_at')
            ->take(5)
            ->get();

        return view('queue.display', compact('sedangDipanggil', 'antrean', 'selesai'));
    }

    public function json(): JsonResponse
    {
        $sedangDipanggil = Pendaftaran::whereDate('tanggal_daftar', today())
            ->whereNotNull('dipanggil_at')
            ->doesntHave('pemeriksaan')
            ->with('pasien')
            ->latest('dipanggil_at')
            ->first();

        $antrean = Pendaftaran::whereDate('tanggal_daftar', today())
            ->doesntHave('pemeriksaan')
            ->whereNull('dipanggil_at')
            ->with('pasien')
            ->orderBy('created_at')
            ->get();

        $selesai = Pendaftaran::whereDate('tanggal_daftar', today())
            ->whereHas('pemeriksaan')
            ->with('pasien')
            ->latest('updated_at')
            ->take(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'sedang_dipanggil' => $sedangDipanggil ? [
                    'nama_pasien' => $sedangDipanggil->pasien->nama_pasien,
                    'dipanggil_at' => $sedangDipanggil->dipanggil_at->format('H:i'),
                ] : null,
                'antrean' => $antrean->map(fn($a) => [
                    'nama_pasien' => $a->pasien->nama_pasien,
                    'created_at' => $a->created_at->format('H:i'),
                ]),
                'selesai' => $selesai->map(fn($s) => [
                    'nama_pasien' => $s->pasien->nama_pasien,
                ]),
            ],
        ]);
    }
}

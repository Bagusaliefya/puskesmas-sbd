<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DaftarController extends Controller
{
    public function landing()
    {
        $status = statusPuskesmas();
        return view('landing', compact('status'));
    }

    public function form()
    {
        $status = statusPuskesmas();
        return view('daftar', compact('status'));
    }

    public function submit(Request $request)
    {
        $status = statusPuskesmas();
        if (! $status['bisa_daftar']) {
            return back()->with('error', $status['pesan']);
        }

        $validated = $request->validate([
            'nama_pasien' => 'required|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
            'golongan_darah' => 'nullable|string|max:5',
            'keluhan' => 'nullable|string',
        ]);

        $pasien = Pasien::create([
            'nama_pasien' => $validated['nama_pasien'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'alamat' => $validated['alamat'],
            'no_hp' => $validated['no_hp'],
            'golongan_darah' => $validated['golongan_darah'],
        ]);

        $pendaftaran = DB::transaction(function () use ($pasien, $validated) {
            $lastAntrian = Pendaftaran::whereDate('tanggal_daftar', today())
                ->lockForUpdate()
                ->max('no_antrian') ?? 0;

            return Pendaftaran::create([
                'id_pasien' => $pasien->id_pasien,
                'no_antrian' => $lastAntrian + 1,
                'tipe_pendaftaran' => 'mandiri',
                'tanggal_daftar' => today(),
                'keluhan' => $validated['keluhan'],
            ]);
        });

        return redirect()->route('daftar.sukses', $pendaftaran->id_pendaftaran);
    }

    public function sukses($id)
    {
        $pendaftaran = Pendaftaran::with([
            'pasien',
            'pemeriksaan.dokter.pegawai',
            'pemeriksaan.resep.detailResep.obat',
        ])->findOrFail($id);

        return view('daftar-sukses', compact('pendaftaran'));
    }

    public function cekResep()
    {
        return view('cek-resep');
    }

    public function cariResep(Request $request)
    {
        $validated = $request->validate([
            'no_antrian' => 'required|integer|min:1',
        ]);

        $pendaftaran = Pendaftaran::where('no_antrian', $validated['no_antrian'])
            ->whereDate('tanggal_daftar', today())
            ->with(['pemeriksaan.dokter.pegawai', 'pemeriksaan.resep.detailResep.obat'])
            ->first();

        if (! $pendaftaran) {
            return back()->withInput()->with('error', 'Antrean tidak ditemukan untuk hari ini.');
        }

        $resep = $pendaftaran?->pemeriksaan?->resep;

        if (! $resep) {
            return back()->withInput()->with('error', 'Belum ada resep untuk pasien ini.');
        }

        return view('cek-resep', compact('resep', 'pendaftaran'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Pemeriksaan;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class PemeriksaanController extends Controller
{
    public function index()
    {
        $dokter = auth()->user()->pegawai?->dokter;
        $pemeriksaan = collect();

        if ($dokter) {
            $pemeriksaan = Pemeriksaan::with(['pendaftaran.pasien', 'resep'])
                ->where('id_dokter', $dokter->id_dokter)
                ->whereDate('tanggal_periksa', today())
                ->latest()
                ->get();
        }

        $daftarPeriksa = Pendaftaran::whereDate('tanggal_daftar', today())
            ->doesntHave('pemeriksaan')
            ->with('pasien')
            ->get();

        return view('dashboard.dokter.pemeriksaan.index', compact('pemeriksaan', 'daftarPeriksa'));
    }

    public function create($id)
    {
        $status = statusPuskesmas();
        if (! $status['bisa_periksa']) {
            return back()->with('error', $status['pesan']);
        }

        $pendaftaran = Pendaftaran::with('pasien')->findOrFail($id);

        return view('dashboard.dokter.pemeriksaan.create', compact('pendaftaran'));
    }

    public function store(Request $request)
    {
        $status = statusPuskesmas();
        if (! $status['bisa_periksa']) {
            return back()->with('error', $status['pesan']);
        }

        $validated = $request->validate([
            'id_pendaftaran' => 'required|exists:pendaftaran,id_pendaftaran',
            'diagnosa' => 'nullable|string',
        ]);

        $dokter = auth()->user()->pegawai?->dokter;

        if (! $dokter) {
            return back()->withErrors(['error' => 'Data dokter tidak ditemukan.']);
        }

        Pemeriksaan::create([
            'id_pendaftaran' => $validated['id_pendaftaran'],
            'id_dokter' => $dokter->id_dokter,
            'diagnosa' => $validated['diagnosa'],
            'tanggal_periksa' => today(),
        ]);

        return redirect()->route('dokter.pemeriksaan.index')->with('success', 'Pemeriksaan berhasil dicatat.');
    }

    public function riwayatPasien($idPasien)
    {
        $pasien = \App\Models\Pasien::with(['pendaftaran.pemeriksaan.dokter.pegawai', 'pendaftaran.pemeriksaan.resep.detailResep.obat'])
            ->findOrFail($idPasien);

        $riwayat = $pasien->pendaftaran()
            ->whereHas('pemeriksaan')
            ->with(['pemeriksaan.dokter.pegawai', 'pemeriksaan.resep.detailResep.obat'])
            ->orderBy('tanggal_daftar', 'desc')
            ->get();

        return view('dashboard.dokter.pemeriksaan.riwayat', compact('pasien', 'riwayat'));
    }

    public function edit($id)
    {
        $pemeriksaan = Pemeriksaan::with(['pendaftaran.pasien', 'dokter.pegawai'])->findOrFail($id);

        return view('dashboard.dokter.pemeriksaan.edit', compact('pemeriksaan'));
    }

    public function update(Request $request, $id)
    {
        $pemeriksaan = Pemeriksaan::findOrFail($id);

        $validated = $request->validate([
            'diagnosa' => 'nullable|string',
        ]);

        $pemeriksaan->update([
            'diagnosa' => $validated['diagnosa'],
        ]);

        return redirect()->route('dokter.pemeriksaan.show', $id)
            ->with('success', 'Diagnosa berhasil diubah.');
    }

    public function destroy($id)
    {
        $pemeriksaan = Pemeriksaan::with('resep.detailResep')->findOrFail($id);

        if ($pemeriksaan->resep) {
            foreach ($pemeriksaan->resep->detailResep as $dr) {
                Obat::where('id_obat', $dr->id_obat)->increment('stok', $dr->jumlah);
            }
            $pemeriksaan->resep->detailResep()->delete();
            $pemeriksaan->resep()->delete();
        }

        $pemeriksaan->delete();

        return redirect()->route('dokter.pemeriksaan.index')
            ->with('success', 'Pemeriksaan berhasil dihapus dan stok obat dikembalikan.');
    }

    public function show($id)
    {
        $pemeriksaan = Pemeriksaan::with([
            'pendaftaran.pasien', 'dokter.pegawai', 'resep.detailResep.obat',
        ])->findOrFail($id);

        return view('dashboard.dokter.pemeriksaan.show', compact('pemeriksaan'));
    }
}

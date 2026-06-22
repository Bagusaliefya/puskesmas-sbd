<?php

namespace App\Http\Controllers;

use App\Models\DetailResep;
use App\Models\Obat;
use App\Models\Pemeriksaan;
use App\Models\Resep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResepController extends Controller
{
    public function create($pemeriksaanId)
    {
        $pemeriksaan = Pemeriksaan::with('pendaftaran.pasien')->findOrFail($pemeriksaanId);
        $obatList = Obat::where('stok', '>', 0)->get();

        return view('dashboard.dokter.resep.create', compact('pemeriksaan', 'obatList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pemeriksaan' => 'required|exists:pemeriksaan,id_pemeriksaan',
            'obat' => 'required|array',
            'obat.*.id_obat' => 'required|exists:obat,id_obat',
            'obat.*.jumlah' => 'required|integer|min:1',
            'obat.*.dosis' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            if (Resep::where('id_pemeriksaan', $validated['id_pemeriksaan'])->lockForUpdate()->exists()) {
                DB::rollBack();
                return back()->withErrors(['error' => 'Resep untuk pemeriksaan ini sudah ada.']);
            }

            foreach ($validated['obat'] as $item) {
                $obat = Obat::where('id_obat', $item['id_obat'])->lockForUpdate()->first();

                if (! $obat || $obat->stok < $item['jumlah']) {
                    DB::rollBack();
                    return back()->withErrors(['error' => "Stok {$obat?->nama_obat} tidak mencukupi. Tersedia: {$obat?->stok}."]);
                }
            }

            $resep = Resep::create([
                'id_pemeriksaan' => $validated['id_pemeriksaan'],
            ]);

            foreach ($validated['obat'] as $item) {
                DetailResep::create([
                    'id_resep' => $resep->id_resep,
                    'id_obat' => $item['id_obat'],
                    'jumlah' => $item['jumlah'],
                    'dosis' => $item['dosis'],
                ]);

                Obat::where('id_obat', $item['id_obat'])->decrement('stok', $item['jumlah']);
            }

            DB::commit();
            return redirect()->route('dokter.pemeriksaan.show', $validated['id_pemeriksaan'])
                ->with('success', 'Resep berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal membuat resep: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $dokter = auth()->user()->pegawai?->dokter;

        DB::beginTransaction();
        try {
            $resep = Resep::where('id_resep', $id)
                ->lockForUpdate()
                ->with('detailResep', 'pemeriksaan')
                ->firstOrFail();

            if (! $dokter || $resep->pemeriksaan->id_dokter !== $dokter->id_dokter) {
                DB::rollBack();
                return back()->with('error', 'Anda tidak berwenang menghapus resep ini.');
            }

            foreach ($resep->detailResep as $dr) {
                Obat::where('id_obat', $dr->id_obat)->increment('stok', $dr->jumlah);
            }

            $idPemeriksaan = $resep->id_pemeriksaan;
            $resep->detailResep()->delete();
            $resep->delete();

            DB::commit();
            return redirect()->route('dokter.pemeriksaan.show', $idPemeriksaan)
                ->with('success', 'Resep berhasil dihapus dan stok obat dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus resep: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $resep = Resep::with([
            'pemeriksaan.pendaftaran.pasien',
            'pemeriksaan.dokter.pegawai',
            'detailResep.obat',
        ])->findOrFail($id);

        return view('dashboard.dokter.resep.show', compact('resep'));
    }
}

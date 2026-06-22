<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PendaftaranController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $rolloverCount = 0;
        if (!$startDate && !$endDate) {
            DB::transaction(function () use (&$rolloverCount) {
                $pending = Pendaftaran::whereDate('tanggal_daftar', '<', today())
                    ->doesntHave('pemeriksaan')
                    ->orderBy('tanggal_daftar')
                    ->orderBy('no_antrian')
                    ->lockForUpdate()
                    ->get();

                if ($pending->isNotEmpty()) {
                    $lastAntrian = Pendaftaran::whereDate('tanggal_daftar', today())
                        ->lockForUpdate()
                        ->max('no_antrian') ?? 0;
                    foreach ($pending as $i => $p) {
                        $p->update([
                            'tanggal_daftar' => today(),
                            'no_antrian' => $lastAntrian + 1 + $i,
                            'dipanggil_at' => null,
                        ]);
                    }
                    $rolloverCount = $pending->count();
                }
            });
        }

        $pendaftaran = Pendaftaran::with(['pasien', 'petugas.pegawai', 'dokter.pegawai'])
            ->when($startDate, fn($q) => $q->whereDate('tanggal_daftar', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('tanggal_daftar', '<=', $endDate))
            ->when(!$startDate && !$endDate, fn($q) => $q->whereDate('tanggal_daftar', today()))
            ->orderByRaw("(select count(*) from pemeriksaan where pemeriksaan.id_pendaftaran = pendaftaran.id_pendaftaran) > 0")
            ->orderByRaw("dipanggil_at is null desc")
            ->latest()
            ->paginate(10);

        return view('dashboard.petugas.pendaftaran.index', compact('pendaftaran', 'startDate', 'endDate', 'rolloverCount'));
    }

    public function create()
    {
        $status = statusPuskesmas();
        $pasienList = Pasien::all();
        $pendingToday = Pendaftaran::whereDate('tanggal_daftar', today())
            ->doesntHave('pemeriksaan')
            ->pluck('id_pasien')
            ->toArray();

        return view('dashboard.petugas.pendaftaran.create', compact('pasienList', 'pendingToday', 'status'));
    }

    public function store(Request $request)
    {
        $status = statusPuskesmas();
        if (! $status['bisa_daftar']) {
            return back()->with('error', $status['pesan']);
        }

        $rules = [
            'keluhan' => 'required|string',
        ];

        if ($request->filled('id_pasien')) {
            $rules['id_pasien'] = 'exists:pasien,id_pasien';
        } else {
            $rules += [
                'nama_pasien' => 'required|string|max:255',
                'tanggal_lahir' => 'nullable|date',
                'jenis_kelamin' => 'nullable|in:L,P',
                'alamat' => 'nullable|string',
                'no_hp' => 'nullable|string|max:20',
                'golongan_darah' => 'nullable|string|max:5',
            ];
        }

        $validated = $request->validate($rules);

        $petugas = auth()->user()->pegawai?->petugas;

        if (! $petugas) {
            return back()->withErrors(['error' => 'Data petugas tidak ditemukan.']);
        }

        if ($request->filled('id_pasien')) {
            $idPasien = $validated['id_pasien'];
        } else {
            $pasien = Pasien::create([
                'nama_pasien' => $validated['nama_pasien'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'alamat' => $validated['alamat'],
                'no_hp' => $validated['no_hp'],
                'golongan_darah' => $validated['golongan_darah'],
            ]);
            $idPasien = $pasien->id_pasien;
        }

        DB::beginTransaction();
        try {
            $existing = Pendaftaran::where('id_pasien', $idPasien)
                ->whereDate('tanggal_daftar', today())
                ->doesntHave('pemeriksaan')
                ->lockForUpdate()
                ->first();

            if ($existing) {
                if ($validated['keluhan']) {
                    $existing->update([
                        'keluhan' => $existing->keluhan
                            ? $existing->keluhan . "\n- " . $validated['keluhan']
                            : $validated['keluhan'],
                    ]);
                }
                DB::commit();
                return redirect()->route('petugas.pendaftaran.index')
                    ->with('success', 'Keluhan ditambahkan ke pendaftaran yang sudah ada.');
            }

            $noAntrian = (Pendaftaran::whereDate('tanggal_daftar', today())
                ->lockForUpdate()
                ->max('no_antrian') ?? 0) + 1;

            Pendaftaran::create([
                'id_pasien' => $idPasien,
                'id_petugas' => $petugas->id_petugas,
                'no_antrian' => $noAntrian,
                'tipe_pendaftaran' => 'petugas',
                'tanggal_daftar' => today(),
                'keluhan' => $validated['keluhan'],
            ]);

            DB::commit();
            return redirect()->route('petugas.pendaftaran.index')->with('success', 'Pendaftaran berhasil.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal mendaftarkan pasien: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $pendaftaran = Pendaftaran::with([
            'pasien', 'petugas.pegawai', 'pemeriksaan.dokter.pegawai',
            'pemeriksaan.resep.detailResep.obat',
        ])->findOrFail($id);

        return view('dashboard.petugas.pendaftaran.show', compact('pendaftaran'));
    }

    public function edit($id)
    {
        $pendaftaran = Pendaftaran::with(['pasien', 'petugas.pegawai'])->findOrFail($id);

        if ($pendaftaran->pemeriksaan) {
            return back()->with('error', 'Tidak bisa mengedit pendaftaran yang sudah diperiksa.');
        }

        $pasienList = Pasien::all();

        return view('dashboard.petugas.pendaftaran.edit', compact('pendaftaran', 'pasienList'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $pendaftaran = Pendaftaran::where('id_pendaftaran', $id)->lockForUpdate()->firstOrFail();

            if ($request->input('updated_at') && $pendaftaran->updated_at->toDateTimeString() !== $request->input('updated_at')) {
                DB::rollBack();
                return back()->withInput()->withErrors([
                    'stale_data' => 'Data pendaftaran telah diubah oleh pengguna lain. Silakan muat ulang halaman dan coba lagi.',
                ]);
            }

            if ($pendaftaran->pemeriksaan) {
                DB::rollBack();
                return back()->with('error', 'Tidak bisa mengubah pendaftaran yang sudah diperiksa.');
            }

            $validated = $request->validate([
                'id_pasien' => 'required|exists:pasien,id_pasien',
                'keluhan' => 'required|string',
            ]);

            $pendaftaran->update([
                'id_pasien' => $validated['id_pasien'],
                'keluhan' => $validated['keluhan'],
            ]);

            DB::commit();
            return redirect()->route('petugas.pendaftaran.index')
                ->with('success', 'Pendaftaran berhasil diubah.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengubah pendaftaran: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $pendaftaran = Pendaftaran::where('id_pendaftaran', $id)->lockForUpdate()->firstOrFail();

            if ($pendaftaran->pemeriksaan) {
                DB::rollBack();
                return back()->with('error', 'Tidak bisa menghapus pendaftaran yang sudah diperiksa.');
            }

            $pendaftaran->delete();

            DB::commit();
            return redirect()->route('petugas.pendaftaran.index')
                ->with('success', 'Pendaftaran berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus pendaftaran: ' . $e->getMessage());
        }
    }

    public function panggil(Request $request, $id)
    {
        $status = statusPuskesmas();
        if (! $status['bisa_periksa']) {
            return back()->with('error', $status['pesan']);
        }

        $validated = $request->validate([
            'id_dokter' => 'required|exists:dokter,id_dokter',
        ]);

        DB::beginTransaction();
        try {
            $pendaftaran = Pendaftaran::where('id_pendaftaran', $id)->lockForUpdate()->firstOrFail();

            if ($pendaftaran->pemeriksaan) {
                DB::rollBack();
                return back()->with('error', 'Pasien sudah diperiksa.');
            }

            $dokter = Dokter::where('id_dokter', $validated['id_dokter'])->lockForUpdate()->firstOrFail();

            if ($dokter->status !== 'tersedia') {
                DB::rollBack();
                return back()->with('error', 'Dokter sedang sibuk.');
            }

            $dokter->update(['status' => 'sibuk']);

            $pendaftaran->update([
                'id_dokter' => $dokter->id_dokter,
                'dipanggil_at' => now(),
            ]);

            DB::commit();
            return back()->with('success', 'Pasien "' . ($pendaftaran->pasien->nama_pasien ?? '') . '" dipanggil untuk dr. ' . ($dokter->pegawai->nama_pegawai ?? '') . '.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memanggil pasien: ' . $e->getMessage());
        }
    }
}

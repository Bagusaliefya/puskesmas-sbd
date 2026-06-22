<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PemeriksaanResource;
use App\Models\Dokter;
use App\Models\Pemeriksaan;
use App\Models\Pendaftaran;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemeriksaanController extends ApiController
{
    public function index(): JsonResponse
    {
        $dokter = auth()->user()->pegawai?->dokter;
        if (! $dokter) {
            return $this->error('Data dokter tidak ditemukan.', 404);
        }

        $pemeriksaan = Pemeriksaan::with(['pendaftaran.pasien', 'dokter.pegawai', 'resep.detailResep.obat'])
            ->where('id_dokter', $dokter->id_dokter)
            ->latest()
            ->paginate(20);

        return $this->success(PemeriksaanResource::collection($pemeriksaan));
    }

    public function show($id): JsonResponse
    {
        $pemeriksaan = Pemeriksaan::with([
            'pendaftaran.pasien', 'dokter.pegawai', 'resep.detailResep.obat',
        ])->findOrFail($id);

        return $this->success(new PemeriksaanResource($pemeriksaan));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id_pendaftaran' => 'required|exists:pendaftaran,id_pendaftaran',
            'diagnosa' => 'nullable|string',
        ]);

        $dokter = auth()->user()->pegawai?->dokter;
        if (! $dokter) {
            return $this->error('Data dokter tidak ditemukan.', 404);
        }

        try {
            $pemeriksaan = DB::transaction(function () use ($validated, $dokter) {
                $pendaftaran = Pendaftaran::where('id_pendaftaran', $validated['id_pendaftaran'])->lockForUpdate()->firstOrFail();

                if (! $pendaftaran->dipanggil_at) {
                    throw new \Exception('Pasien belum dipanggil.');
                }

                if ($pendaftaran->pemeriksaan) {
                    throw new \Exception('Pasien sudah diperiksa.');
                }

                $pemeriksaan = Pemeriksaan::create([
                    'id_pendaftaran' => $validated['id_pendaftaran'],
                    'id_dokter' => $dokter->id_dokter,
                    'diagnosa' => $validated['diagnosa'],
                    'tanggal_periksa' => today(),
                ]);

                $masihAdaTugas = Pendaftaran::where('id_dokter', $dokter->id_dokter)
                    ->whereDate('tanggal_daftar', today())
                    ->whereNotNull('dipanggil_at')
                    ->doesntHave('pemeriksaan')
                    ->lockForUpdate()
                    ->exists();

                if (! $masihAdaTugas) {
                    Dokter::where('id_dokter', $dokter->id_dokter)
                        ->lockForUpdate()
                        ->update(['status' => 'tersedia']);
                }

                return $pemeriksaan;
            });

            return $this->success(new PemeriksaanResource($pemeriksaan), 'Pemeriksaan berhasil.', 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }
    }

    public function daftarPeriksa(): JsonResponse
    {
        $daftar = Pendaftaran::whereDate('tanggal_daftar', today())
            ->whereNotNull('dipanggil_at')
            ->doesntHave('pemeriksaan')
            ->with('pasien')
            ->orderBy('dipanggil_at')
            ->get();

        return $this->success(\App\Http\Resources\PendaftaranResource::collection($daftar));
    }
}

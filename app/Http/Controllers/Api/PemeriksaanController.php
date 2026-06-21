<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PemeriksaanResource;
use App\Models\Pemeriksaan;
use App\Models\Pendaftaran;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PemeriksaanController extends ApiController
{
    public function index(): JsonResponse
    {
        $dokter = auth()->user()->pegawai?->dokter;
        if (! $dokter) {
            return $this->error('Data dokter tidak ditemukan.', 404);
        }

        $pemeriksaan = Pemeriksaan::with(['pendaftaran.pasien', 'resep.detailResep.obat'])
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

        $pemeriksaan = Pemeriksaan::create([
            'id_pendaftaran' => $validated['id_pendaftaran'],
            'id_dokter' => $dokter->id_dokter,
            'diagnosa' => $validated['diagnosa'],
            'tanggal_periksa' => today(),
        ]);

        return $this->success(new PemeriksaanResource($pemeriksaan), 'Pemeriksaan berhasil.', 201);
    }

    public function daftarPeriksa(): JsonResponse
    {
        $daftar = Pendaftaran::whereDate('tanggal_daftar', today())
            ->doesntHave('pemeriksaan')
            ->with('pasien')
            ->orderBy('created_at')
            ->get();

        return $this->success(\App\Http\Resources\PendaftaranResource::collection($daftar));
    }
}

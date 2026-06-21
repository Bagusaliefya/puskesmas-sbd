<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PendaftaranResource;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PendaftaranController extends ApiController
{
    public function index(): JsonResponse
    {
        $pendaftaran = Pendaftaran::with('pasien')
            ->whereDate('tanggal_daftar', today())
            ->latest()
            ->paginate(20);

        return $this->success(PendaftaranResource::collection($pendaftaran));
    }

    public function show($id): JsonResponse
    {
        $pendaftaran = Pendaftaran::with(['pasien', 'petugas.pegawai', 'pemeriksaan.dokter.pegawai', 'pemeriksaan.resep.detailResep.obat'])
            ->findOrFail($id);

        return $this->success(new PendaftaranResource($pendaftaran));
    }

    public function store(Request $request): JsonResponse
    {
        $status = statusPuskesmas();
        if (! $status['bisa_daftar']) {
            return $this->error($status['pesan'], 403);
        }

        $rules = ['keluhan' => 'nullable|string'];

        if ($request->filled('id_pasien')) {
            $rules['id_pasien'] = 'exists:pasien,id_pasien';
        } else {
            $rules += [
                'nama_pasien' => 'required|string|max:255',
                'tanggal_lahir' => 'nullable|date',
                'jenis_kelamin' => 'nullable|in:L,P',
                'alamat' => 'nullable|string',
                'no_hp' => 'nullable|string|max:20',
            ];
        }

        $validated = $request->validate($rules);

        $petugas = auth()->user()->pegawai?->petugas;
        if (! $petugas) {
            return $this->error('Data petugas tidak ditemukan.', 404);
        }

        if ($request->filled('id_pasien')) {
            $idPasien = $validated['id_pasien'];
        } else {
            $pasien = Pasien::create([
                'nama_pasien' => $validated['nama_pasien'],
                'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
                'no_hp' => $validated['no_hp'] ?? null,
            ]);
            $idPasien = $pasien->id_pasien;
        }

        $pendaftaran = Pendaftaran::create([
            'id_pasien' => $idPasien,
            'id_petugas' => $petugas->id_petugas,
            'tanggal_daftar' => today(),
            'keluhan' => $validated['keluhan'],
            'tipe_pendaftaran' => 'petugas',
        ]);

        return $this->success(new PendaftaranResource($pendaftaran), 'Pendaftaran berhasil.', 201);
    }

    public function antrian(): JsonResponse
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

        return $this->success([
            'sedang_dipanggil' => $sedangDipanggil ? new PendaftaranResource($sedangDipanggil) : null,
            'antrean' => PendaftaranResource::collection($antrean),
        ]);
    }
}

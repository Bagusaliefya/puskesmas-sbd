<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PasienResource;
use App\Models\Pasien;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PasienController extends ApiController
{
    public function index(): JsonResponse
    {
        $pasien = Pasien::latest()->paginate(20);
        return $this->success(PasienResource::collection($pasien));
    }

    public function show(Pasien $pasien): JsonResponse
    {
        $pasien->load('pendaftaran.pemeriksaan');
        return $this->success(new PasienResource($pasien));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama_pasien' => 'required|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
            'golongan_darah' => 'nullable|string|max:5',
        ]);

        $pasien = Pasien::create($validated);

        return $this->success(new PasienResource($pasien), 'Pasien berhasil ditambahkan.', 201);
    }

    public function update(Request $request, Pasien $pasien): JsonResponse
    {
        $validated = $request->validate([
            'nama_pasien' => 'required|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
            'golongan_darah' => 'nullable|string|max:5',
        ]);

        $pasien->update($validated);

        return $this->success(new PasienResource($pasien), 'Pasien berhasil diperbarui.');
    }

    public function destroy(Pasien $pasien): JsonResponse
    {
        $pasien->delete();
        return $this->success(null, 'Pasien berhasil dihapus.');
    }

    public function search(Request $request): JsonResponse
    {
        $q = $request->input('q');
        $pasien = Pasien::where('nama_pasien', 'like', "%{$q}%")
            ->orWhere('no_hp', 'like', "%{$q}%")
            ->limit(20)
            ->get();

        return $this->success(PasienResource::collection($pasien));
    }
}

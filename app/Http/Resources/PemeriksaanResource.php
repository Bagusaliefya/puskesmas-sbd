<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PemeriksaanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_pemeriksaan' => $this->id_pemeriksaan,
            'pendaftaran' => new PendaftaranResource($this->whenLoaded('pendaftaran')),
            'dokter' => $this->dokter?->pegawai?->nama_pegawai,
            'diagnosa' => $this->diagnosa,
            'tanggal_periksa' => $this->tanggal_periksa,
            'resep' => ResepResource::make($this->whenLoaded('resep')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

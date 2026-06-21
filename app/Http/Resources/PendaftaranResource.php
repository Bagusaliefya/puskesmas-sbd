<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PendaftaranResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_pendaftaran' => $this->id_pendaftaran,
            'pasien' => new PasienResource($this->whenLoaded('pasien')),
            'tanggal_daftar' => $this->tanggal_daftar,
            'keluhan' => $this->keluhan,
            'tipe_pendaftaran' => $this->tipe_pendaftaran,
            'dipanggil_at' => $this->dipanggil_at,
            'status' => $this->pemeriksaan ? 'selesai' : ($this->dipanggil_at ? 'dipanggil' : 'menunggu'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

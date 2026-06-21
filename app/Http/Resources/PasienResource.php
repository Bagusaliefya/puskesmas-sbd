<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PasienResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_pasien' => $this->id_pasien,
            'nama_pasien' => $this->nama_pasien,
            'tanggal_lahir' => $this->tanggal_lahir,
            'jenis_kelamin' => $this->jenis_kelamin,
            'alamat' => $this->alamat,
            'no_hp' => $this->no_hp,
            'golongan_darah' => $this->golongan_darah,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

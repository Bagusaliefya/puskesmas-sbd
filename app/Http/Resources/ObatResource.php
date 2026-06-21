<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ObatResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_obat' => $this->id_obat,
            'nama_obat' => $this->nama_obat,
            'stok' => $this->stok,
            'stok_minimum' => $this->stok_minimum,
            'harga' => $this->harga,
            'stok_menipis' => $this->stokMenipis(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

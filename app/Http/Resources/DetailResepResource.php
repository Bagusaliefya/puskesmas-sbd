<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailResepResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_detail' => $this->id_detail,
            'obat' => $this->obat?->nama_obat,
            'jumlah' => $this->jumlah,
            'dosis' => $this->dosis,
        ];
    }
}

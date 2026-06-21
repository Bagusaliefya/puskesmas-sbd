<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResepResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_resep' => $this->id_resep,
            'detail_resep' => DetailResepResource::collection($this->whenLoaded('detailResep')),
            'created_at' => $this->created_at,
        ];
    }
}

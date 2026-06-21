<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ObatResource;
use App\Models\Obat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ObatController extends ApiController
{
    public function index(): JsonResponse
    {
        $obat = Obat::latest()->paginate(20);
        return $this->success(ObatResource::collection($obat));
    }

    public function show(Obat $obat): JsonResponse
    {
        return $this->success(new ObatResource($obat));
    }

    public function hampirHabis(): JsonResponse
    {
        $obat = Obat::hampirHabis()->get();
        return $this->success(ObatResource::collection($obat));
    }
}

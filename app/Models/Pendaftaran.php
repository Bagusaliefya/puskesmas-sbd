<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    protected $table = 'pendaftaran';

    protected $primaryKey = 'id_pendaftaran';

    protected $fillable = [
        'id_pasien',
        'id_petugas',
        'id_dokter',
        'no_antrian',
        'tipe_pendaftaran',
        'tanggal_daftar',
        'keluhan',
        'dipanggil_at',
    ];

    protected function casts(): array
    {
        return [
            'dipanggil_at' => 'datetime',
        ];
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'id_pasien', 'id_pasien');
    }

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'id_petugas', 'id_petugas');
    }

    public function pemeriksaan()
    {
        return $this->hasOne(Pemeriksaan::class, 'id_pendaftaran', 'id_pendaftaran');
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'id_dokter', 'id_dokter');
    }
}

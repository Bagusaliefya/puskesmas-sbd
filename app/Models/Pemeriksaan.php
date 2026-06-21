<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemeriksaan extends Model
{
    protected $table = 'pemeriksaan';

    protected $primaryKey = 'id_pemeriksaan';

    protected $fillable = [
        'id_pendaftaran',
        'id_dokter',
        'diagnosa',
        'tanggal_periksa',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'id_pendaftaran', 'id_pendaftaran');
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'id_dokter', 'id_dokter');
    }

    public function resep()
    {
        return $this->hasOne(Resep::class, 'id_pemeriksaan', 'id_pemeriksaan');
    }
}

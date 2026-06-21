<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    protected $table = 'dokter';

    protected $primaryKey = 'id_dokter';

    protected $fillable = [
        'id_pegawai',
        'spesialisasi',
        'status',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }

    public function pemeriksaan()
    {
        return $this->hasMany(Pemeriksaan::class, 'id_dokter', 'id_dokter');
    }

    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class, 'id_dokter', 'id_dokter');
    }

    public function scopeTersedia($query)
    {
        return $query->where('status', 'tersedia');
    }
}

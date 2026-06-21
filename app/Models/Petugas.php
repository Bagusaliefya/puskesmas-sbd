<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    protected $table = 'petugas';

    protected $primaryKey = 'id_petugas';

    protected $fillable = [
        'id_pegawai',
        'loket',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }

    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class, 'id_petugas', 'id_petugas');
    }
}

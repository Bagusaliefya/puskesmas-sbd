<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';

    protected $primaryKey = 'id_pegawai';

    protected $fillable = [
        'nama_pegawai',
        'jabatan',
        'no_hp',
        'tanggal_masuk',
        'alamat',
    ];

    public function petugas()
    {
        return $this->hasOne(Petugas::class, 'id_pegawai', 'id_pegawai');
    }

    public function dokter()
    {
        return $this->hasOne(Dokter::class, 'id_pegawai', 'id_pegawai');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    protected $table = 'pasien';

    protected $primaryKey = 'id_pasien';

    protected $fillable = [
        'nama_pasien',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'no_hp',
        'golongan_darah',
    ];

    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class, 'id_pasien', 'id_pasien');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resep extends Model
{
    protected $table = 'resep';

    protected $primaryKey = 'id_resep';

    protected $fillable = [
        'id_pemeriksaan',
    ];

    public function pemeriksaan()
    {
        return $this->belongsTo(Pemeriksaan::class, 'id_pemeriksaan', 'id_pemeriksaan');
    }

    public function detailResep()
    {
        return $this->hasMany(DetailResep::class, 'id_resep', 'id_resep');
    }
}

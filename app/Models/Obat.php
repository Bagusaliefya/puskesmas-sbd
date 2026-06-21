<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    protected $table = 'obat';

    protected $primaryKey = 'id_obat';

    protected $fillable = [
        'nama_obat',
        'stok',
        'stok_minimum',
        'harga',
    ];

    public function stokMenipis(): bool
    {
        return $this->stok <= $this->stok_minimum;
    }

    public function scopeHampirHabis($query)
    {
        return $query->whereColumn('stok', '<=', 'stok_minimum');
    }

    public function detailResep()
    {
        return $this->hasMany(DetailResep::class, 'id_obat', 'id_obat');
    }
}

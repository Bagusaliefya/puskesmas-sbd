<?php

namespace Database\Seeders;

use App\Models\Obat;
use Illuminate\Database\Seeder;

class ObatSeeder extends Seeder
{
    public function run(): void
    {
        Obat::create(['nama_obat' => 'Paracetamol 500mg', 'stok' => 100, 'harga' => 5000]);
        Obat::create(['nama_obat' => 'Amoxicillin 500mg', 'stok' => 80, 'harga' => 12000]);
        Obat::create(['nama_obat' => 'Ibuprofen 400mg', 'stok' => 60, 'harga' => 8000]);
        Obat::create(['nama_obat' => 'Vitamin C 100mg', 'stok' => 200, 'harga' => 3000]);
        Obat::create(['nama_obat' => 'Antasida Tablet', 'stok' => 90, 'harga' => 4000]);
    }
}

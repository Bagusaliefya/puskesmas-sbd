<?php

namespace Database\Seeders;

use App\Models\Pasien;
use Illuminate\Database\Seeder;

class PasienSeeder extends Seeder
{
    public function run(): void
    {
        Pasien::create([
            'nama_pasien' => 'Budi Santoso',
            'tanggal_lahir' => '1990-05-12',
            'jenis_kelamin' => 'L',
            'alamat' => 'Jl. Kenanga No.3',
            'no_hp' => '085712345678',
            'golongan_darah' => 'A',
        ]);
        Pasien::create([
            'nama_pasien' => 'Ani Wijaya',
            'tanggal_lahir' => '1995-08-22',
            'jenis_kelamin' => 'P',
            'alamat' => 'Jl. Melati No.7',
            'no_hp' => '085712345679',
            'golongan_darah' => 'B',
        ]);
        Pasien::create([
            'nama_pasien' => 'Citra Dewi',
            'tanggal_lahir' => '2000-01-15',
            'jenis_kelamin' => 'P',
            'alamat' => 'Jl. Mawar No.12',
            'no_hp' => '085712345680',
            'golongan_darah' => 'O',
        ]);
    }
}

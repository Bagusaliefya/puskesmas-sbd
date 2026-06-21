<?php

namespace Database\Seeders;

use App\Models\Dokter;
use App\Models\Pegawai;
use App\Models\Petugas;
use App\Models\User;
use Illuminate\Database\Seeder;

class PegawaiSeeder extends Seeder
{
    public function run(): void
    {
        $p1 = Pegawai::create([
            'nama_pegawai' => 'Petugas',
            'jabatan' => 'Petugas Loket',
            'no_hp' => '081234567891',
            'tanggal_masuk' => '2024-01-15',
            'alamat' => 'Jl. Merdeka No.1',
        ]);
        Petugas::create([
            'id_pegawai' => $p1->id_pegawai,
            'loket' => 'Loket 1',
        ]);
        $user1 = new User([
            'name' => 'Petugas',
            'email' => 'petugas@puskesmas.test',
            'password' => 'password',
            'id_pegawai' => $p1->id_pegawai,
        ]);
        $user1->role = 'petugas';
        $user1->save();
        $user1->assignRole('petugas');

        $d1 = Pegawai::create([
            'nama_pegawai' => 'Dokter',
            'jabatan' => 'Dokter Umum',
            'no_hp' => '081234567892',
            'tanggal_masuk' => '2024-02-01',
            'alamat' => 'Jl. Sudirman No.10',
        ]);
        Dokter::create([
            'id_pegawai' => $d1->id_pegawai,
            'spesialisasi' => 'Umum',
        ]);
        $user2 = new User([
            'name' => 'Dokter',
            'email' => 'dokter@puskesmas.test',
            'password' => 'password',
            'id_pegawai' => $d1->id_pegawai,
        ]);
        $user2->role = 'dokter';
        $user2->save();
        $user2->assignRole('dokter');
    }
}

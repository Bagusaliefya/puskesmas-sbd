<?php

namespace Database\Seeders;

use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\Pemeriksaan;
use App\Models\Resep;
use App\Models\DetailResep;
use Illuminate\Database\Seeder;

class PendaftaranSeeder extends Seeder
{
    public function run(): void
    {
        DetailResep::get()->each(fn ($m) => $m->delete());
        Resep::get()->each(fn ($m) => $m->delete());
        Pemeriksaan::get()->each(fn ($m) => $m->delete());
        Pendaftaran::get()->each(fn ($m) => $m->delete());
        Pasien::get()->each(fn ($m) => $m->delete());

        $this->call(PasienSeeder::class);

        $pasien = Pasien::all();

        $petugasId = 1;

        $now = now()->setHour(7)->setMinute(30)->setSecond(0);

        Pendaftaran::create([
            'id_pasien' => $pasien[0]->id_pasien,
            'id_petugas' => $petugasId,
            'no_antrian' => 1,
            'tipe_pendaftaran' => 'petugas',
            'tanggal_daftar' => today(),
            'keluhan' => 'Demam dan batuk sejak 3 hari',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $now = $now->addMinutes(15);

        Pendaftaran::create([
            'id_pasien' => $pasien[1]->id_pasien,
            'id_petugas' => $petugasId,
            'no_antrian' => 2,
            'tipe_pendaftaran' => 'petugas',
            'tanggal_daftar' => today(),
            'keluhan' => 'Sakit kepala dan pusing',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $now = $now->addMinutes(10);

        Pendaftaran::create([
            'id_pasien' => $pasien[2]->id_pasien,
            'id_petugas' => $petugasId,
            'no_antrian' => 3,
            'tipe_pendaftaran' => 'mandiri',
            'tanggal_daftar' => today(),
            'keluhan' => 'Nyeri perut bagian bawah',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $now = $now->addMinutes(20);

        Pendaftaran::create([
            'id_pasien' => $pasien[0]->id_pasien,
            'id_petugas' => $petugasId,
            'no_antrian' => 4,
            'tipe_pendaftaran' => 'petugas',
            'tanggal_daftar' => today(),
            'keluhan' => 'Kontrol lanjutan tekanan darah',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $now = $now->addMinutes(5);

        Pendaftaran::create([
            'id_pasien' => $pasien[1]->id_pasien,
            'id_petugas' => $petugasId,
            'no_antrian' => 5,
            'tipe_pendaftaran' => 'mandiri',
            'tanggal_daftar' => today(),
            'keluhan' => 'Luka di kaki kanan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}

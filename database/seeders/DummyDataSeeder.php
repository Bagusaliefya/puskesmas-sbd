<?php

namespace Database\Seeders;

use App\Models\DetailResep;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Pemeriksaan;
use App\Models\Pendaftaran;
use App\Models\Resep;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $pasien = Pasien::first() ?? Pasien::create([
            'nama_pasien' => 'Budi Santoso',
            'tanggal_lahir' => '1990-05-15',
            'jenis_kelamin' => 'L',
            'alamat' => 'Jl. Merdeka No. 1',
            'no_hp' => '081234567890',
        ]);

        $obats = Obat::all();
        if ($obats->isEmpty()) {
            return;
        }

        $bulanNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $year = date('Y');

        foreach (range(1, 12) as $bulan) {
            $count = rand(3, 10);

            for ($i = 0; $i < $count; $i++) {
                $day = rand(1, 28);
                $date = Carbon::create($year, $bulan, $day);

                $pendaftaran = Pendaftaran::create([
                    'id_pasien' => $pasien->id_pasien,
                    'tanggal_daftar' => $date->format('Y-m-d'),
                    'keluhan' => 'Demam dan batuk selama ' . rand(2, 7) . ' hari',
                    'tipe_pendaftaran' => rand(0, 1) ? 'mandiri' : 'petugas',
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);

                $pemeriksaan = Pemeriksaan::create([
                    'id_pendaftaran' => $pendaftaran->id_pendaftaran,
                    'id_dokter' => 1,
                    'diagnosa' => 'ISPA ringan',
                    'tanggal_periksa' => $date,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);

                if (rand(0, 1)) {
                    $resep = Resep::create([
                        'id_pemeriksaan' => $pemeriksaan->id_pemeriksaan,
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);

                    foreach ($obats->random(rand(1, 3)) as $obat) {
                        $jml = rand(1, 3);
                        DetailResep::create([
                            'id_resep' => $resep->id_resep,
                            'id_obat' => $obat->id_obat,
                            'jumlah' => $jml,
                            'dosis' => rand(1, 3) . 'x sehari ' . rand(1, 2) . ' tablet',
                            'created_at' => $date,
                            'updated_at' => $date,
                        ]);
                    }
                }
            }
        }

        $this->command->info('Dummy data for ' . $year . ' created successfully!');
    }
}

<?php

namespace Database\Seeders;

use App\Models\DetailResep;
use App\Models\Dokter;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Pegawai;
use App\Models\Pemeriksaan;
use App\Models\Pendaftaran;
use App\Models\Petugas;
use App\Models\Resep;
use App\Models\User;
use Illuminate\Database\Seeder;

class ResetDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Mulai reset data...');

        // =====================
        // 1. HAPUS DATA PASIEN
        // =====================
        DetailResep::query()->delete();
        Resep::query()->delete();
        Pemeriksaan::query()->delete();
        Pendaftaran::query()->delete();
        Pasien::query()->delete();

        $this->command->info('Semua data pasien dan transaksi berhasil dihapus.');

        // ============================
        // 2. DUMMY DATA OBAT
        // ============================
        Obat::query()->delete();

        $dummyObat = [
            ['nama_obat' => 'Paracetamol 500mg',     'stok' => 150, 'stok_minimum' => 20],
            ['nama_obat' => 'Amoxicillin 500mg',     'stok' => 100, 'stok_minimum' => 15],
            ['nama_obat' => 'Ibuprofen 400mg',       'stok' => 80,  'stok_minimum' => 10],
            ['nama_obat' => 'Vitamin C 100mg',       'stok' => 200, 'stok_minimum' => 30],
            ['nama_obat' => 'Antasida Tablet',       'stok' => 120, 'stok_minimum' => 15],
            ['nama_obat' => 'Ambroxol Sirup 60ml',   'stok' => 60,  'stok_minimum' => 10],
            ['nama_obat' => 'CTM Tablet',            'stok' => 100, 'stok_minimum' => 20],
            ['nama_obat' => 'Metformin 500mg',       'stok' => 90,  'stok_minimum' => 10],
            ['nama_obat' => 'Amlodipin 5mg',         'stok' => 70,  'stok_minimum' => 10],
            ['nama_obat' => 'Salbutamol Inhaler',    'stok' => 40,  'stok_minimum' => 5],
            ['nama_obat' => 'Ranitidin 150mg',       'stok' => 85,  'stok_minimum' => 10],
            ['nama_obat' => 'Dexametason 0.5mg',     'stok' => 75,  'stok_minimum' => 10],
        ];

        foreach ($dummyObat as $data) {
            Obat::create($data);
        }

        $this->command->info(count($dummyObat) . ' data obat dummy berhasil dibuat.');

        // ============================================
        // 3. MANAJEMEN USER - SISAKAN 1 PETUGAS, 1 DOKTER
        // ============================================

        // 3a. Pastikan 1 petugas
        $petugas = Petugas::first();
        if (!$petugas) {
            $peg = Pegawai::create([
                'nama_pegawai'  => 'Petugas',
                'jabatan'       => 'Petugas Loket',
                'no_hp'         => '081234567891',
                'tanggal_masuk' => '2024-01-15',
                'alamat'        => 'Jl. Merdeka No.1',
            ]);
            $petugas = Petugas::create([
                'id_pegawai' => $peg->id_pegawai,
                'loket'      => 'Loket 1',
            ]);
            $user = User::create([
                'name'       => 'Petugas',
                'email'      => 'petugas@puskesmas.test',
                'password'   => 'password',
                'role'       => 'petugas',
                'id_pegawai' => $peg->id_pegawai,
            ]);
            $user->assignRole('petugas');
            $this->command->info('Petugas baru dibuat.');
        } else {
            // Hapus petugas lain
            Petugas::where('id_petugas', '!=', $petugas->id_petugas)->delete();
            $this->command->info('Petugas dipertahankan (id_petugas=' . $petugas->id_petugas . ').');
        }

        // 3b. Pastikan 1 dokter
        $dokter = Dokter::first();
        if (!$dokter) {
            $peg = Pegawai::create([
                'nama_pegawai'  => 'Dokter',
                'jabatan'       => 'Dokter Umum',
                'no_hp'         => '081234567892',
                'tanggal_masuk' => '2024-02-01',
                'alamat'        => 'Jl. Sudirman No.10',
            ]);
            $dokter = Dokter::create([
                'id_pegawai'    => $peg->id_pegawai,
                'spesialisasi'  => 'Umum',
            ]);
            $user = User::create([
                'name'       => 'Dokter',
                'email'      => 'dokter@puskesmas.test',
                'password'   => 'password',
                'role'       => 'dokter',
                'id_pegawai' => $peg->id_pegawai,
            ]);
            $user->assignRole('dokter');
            $this->command->info('Dokter baru dibuat.');
        } else {
            Dokter::where('id_dokter', '!=', $dokter->id_dokter)->delete();
            $this->command->info('Dokter dipertahankan (id_dokter=' . $dokter->id_dokter . ').');
        }

        // 3c. Hapus pegawai yang tidak terpakai (cascade hapus petugas/dokter/user terkait)
        $keepPegawaiIds = collect([
            $petugas->id_pegawai,
            $dokter->id_pegawai,
        ]);
        Pegawai::whereNotIn('id_pegawai', $keepPegawaiIds)->delete();

        // 3d. Hapus user yang tidak punya pegawai dan bukan admin
        User::whereNull('id_pegawai')->where('role', '!=', 'admin')->delete();

        // 3e. Pastikan admin ada
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::create([
                'name'     => 'Admin Puskesmas',
                'email'    => 'admin@puskesmas.test',
                'password' => 'password',
                'role'     => 'admin',
            ]);
            $admin->assignRole('admin');
            $this->command->info('Admin baru dibuat.');
        } else {
            $this->command->info('Admin dipertahankan.');
        }

        $this->command->info('Manajemen user selesai. Tersisa: 1 admin, 1 petugas, 1 dokter.');
        $this->command->info('Reset data selesai!');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'manage-pegawai']);
        Permission::create(['name' => 'manage-pasien']);
        Permission::create(['name' => 'manage-obat']);
        Permission::create(['name' => 'manage-pendaftaran']);
        Permission::create(['name' => 'manage-pemeriksaan']);
        Permission::create(['name' => 'manage-resep']);
        Permission::create(['name' => 'view-laporan']);

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo([
            'manage-pegawai', 'manage-pasien', 'manage-obat',
            'manage-pendaftaran', 'manage-pemeriksaan', 'manage-resep',
            'view-laporan',
        ]);

        $petugas = Role::create(['name' => 'petugas']);
        $petugas->givePermissionTo(['manage-pasien', 'manage-pendaftaran']);

        $dokter = Role::create(['name' => 'dokter']);
        $dokter->givePermissionTo(['manage-pemeriksaan', 'manage-resep']);
    }
}

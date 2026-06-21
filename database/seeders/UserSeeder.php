<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin Puskesmas',
            'email' => 'admin@puskesmas.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
        $admin->assignRole('admin');
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = new User([
            'name' => 'Admin Puskesmas',
            'email' => 'admin@puskesmas.test',
            'password' => 'password',
        ]);
        $admin->role = 'admin';
        $admin->save();
        $admin->assignRole('admin');
    }
}

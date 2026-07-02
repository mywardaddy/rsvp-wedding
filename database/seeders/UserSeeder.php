<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superadminRole = Role::where('name', 'superadmin')->first();
        $pengantinRole = Role::where('name', 'pengantin')->first();
        $petugasRole = Role::where('name', 'petugas_scan')->first();

        User::updateOrCreate(
            ['email' => 'admin@wedding.test'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role_id' => $superadminRole->id,
                'phone' => '081234567890',
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'pengantin@wedding.test'],
            [
                'name' => 'Ahmad & Siti',
                'password' => Hash::make('password'),
                'role_id' => $pengantinRole->id,
                'phone' => '081234567891',
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'petugas@wedding.test'],
            [
                'name' => 'Petugas Scanner',
                'password' => Hash::make('password'),
                'role_id' => $petugasRole->id,
                'phone' => '081234567892',
                'is_active' => true,
            ]
        );
    }
}

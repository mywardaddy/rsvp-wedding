<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'superadmin', 'display_name' => 'Super Admin', 'description' => 'Administrator sistem dengan akses penuh'],
            ['name' => 'pengantin', 'display_name' => 'Pengantin', 'description' => 'Pasangan pengantin yang mengelola acara'],
            ['name' => 'petugas_scan', 'display_name' => 'Petugas Scan', 'description' => 'Petugas yang melakukan scan QR Code'],
            ['name' => 'tamu', 'display_name' => 'Tamu', 'description' => 'Tamu undangan'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['name' => $role['name']], $role);
        }
    }
}

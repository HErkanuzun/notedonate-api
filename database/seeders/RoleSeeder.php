<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * RoleSeeder'da varsayılan roller tanımlandı.
     */
    public function run(): void
    {
        // Super Admin rolü
        Role::create([
            'name' => 'super_admin',
            'description' => 'Tüm yetkilere sahip süper yönetici'
        ]);

        // Admin rolü
        Role::create([
            'name' => 'admin',
            'description' => 'Site yöneticisi'
        ]);

        // User rolü
        Role::create([
            'name' => 'user',
            'description' => 'Normal kullanıcı'
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'Super Admin',
            'Admin',
            'Editor',
            'User'
        ];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
        // Hiçbir kullanıcıya otomatik rol atanmaz!
    }
}

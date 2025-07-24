<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // Kullanıcı yönetimi
            'users.view', 'users.create', 'users.edit', 'users.delete',
            // Haber yönetimi
            'news.view', 'news.create', 'news.edit', 'news.delete',
            // Kategori yönetimi
            'categories.view', 'categories.create', 'categories.edit', 'categories.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $admin      = Role::firstOrCreate(['name' => 'Admin']);
        $editor     = Role::firstOrCreate(['name' => 'Editor']);
        $user       = Role::firstOrCreate(['name' => 'User']); // YENİ

        $superAdmin->givePermissionTo(Permission::all());
        $admin->givePermissionTo([
            'users.view', 'users.create', 'users.edit', 'users.delete',
            'news.view', 'news.create', 'news.edit', 'news.delete',
            'categories.view', 'categories.create', 'categories.edit', 'categories.delete',
        ]);
        $editor->givePermissionTo([
            'news.view', 'news.create', 'news.edit', 'news.delete',
        ]);
        // User rolüne hiçbir izin verilmedi!
    }
}

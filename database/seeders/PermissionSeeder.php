<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Tüm izinleri oluştur
        $permissions = [
            // Kullanıcı yönetimi
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',

            // Haber yönetimi
            'news.view',
            'news.create',
            'news.edit',
            'news.delete',

            // Kategori yönetimi
            'categories.view',
            'categories.create',
            'categories.edit',
            'categories.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Rolleri oluştur
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $editor = Role::firstOrCreate(['name' => 'Editor']);

        // Super Admin tüm yetkilere sahip
        $superAdmin->givePermissionTo(Permission::all());

        // Admin kullanıcı yönetimi hariç tüm yetkilere sahip
        $admin->givePermissionTo([
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'news.view',
            'news.create',
            'news.edit',
            'news.delete',
            'categories.view',
            'categories.create',
            'categories.edit',
            'categories.delete',
        ]);

        // Editor sadece haber yönetimi yetkilerine sahip
        $editor->givePermissionTo([
            'news.view',
            'news.create',
            'news.edit',
            'news.delete',
        ]);
    }
}
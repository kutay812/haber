<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UpdatePermissions extends Command
{
    protected $signature = 'permissions:update';
    protected $description = 'Update permissions for all roles without affecting existing users';

    public function handle()
    {
        $this->info('Updating permissions...');

        // Yeni permission'ları oluştur
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
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Rolleri güncelle
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $editor = Role::firstOrCreate(['name' => 'Editor']);

        // Super Admin tüm yetkilere sahip
        $superAdmin->syncPermissions(Permission::all());

        // Admin tüm yetkilere sahip (Super Admin yönetimi hariç)
        $admin->syncPermissions([
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
        $editor->syncPermissions([
            'news.view',
            'news.create',
            'news.edit',
            'news.delete',
            'categories.view', // Kategorileri sadece görüntüleyebilir
        ]);

        $this->info('Permissions updated successfully!');
        $this->info('Admin role now has: users.*, news.*, categories.* permissions');
    }
}
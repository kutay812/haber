<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignAdminRole extends Command
{
    protected $signature = 'role:assign-admin {email}';
    protected $description = 'Belirtilen e-posta adresine sahip kullanıcıya admin rolü atar';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("Kullanıcı bulunamadı: {$email}");
            return 1;
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        
        if ($user->hasRole('admin')) {
            $this->info("Kullanıcı zaten admin rolüne sahip: {$email}");
            return 0;
        }

        $user->assignRole($adminRole);
        $this->info("Admin rolü başarıyla atandı: {$email}");

        return 0;
    }
} 
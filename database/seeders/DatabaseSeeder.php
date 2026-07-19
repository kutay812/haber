<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\ImagesSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\MainCategorySeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\NewsSeeder;
use Database\Seeders\NewsSourceSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,        // First create roles
            PermissionSeeder::class,  // Then create and assign permissions
            UserSeeder::class,        // Then create users
            ImagesSeeder::class,
            MainCategorySeeder::class,
            CategorySeeder::class,
            NewsSourceSeeder::class,  // Added national RSS sources
            NewsSeeder::class,
        ]);
    }
}

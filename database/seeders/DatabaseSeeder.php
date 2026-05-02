<?php

namespace Database\Seeders;

use Database\Seeders\User\AdminSeeder;
use Database\Seeders\User\PermissionSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            /** USER */
            PermissionSeeder::class,
            AdminSeeder::class,
        ]);
    }
}

<?php

namespace Database\Seeders\User;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();

        $superadmin = User::create([
            'name' => 'Admin',
            'email' => 'superadmin@nextcrm.com',
            'password' => bcrypt('password')
        ]);

        $superadmin->assignRole('superadmin');

        Schema::enableForeignKeyConstraints();
    }
}

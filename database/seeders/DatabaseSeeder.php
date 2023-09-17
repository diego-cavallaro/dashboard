<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->call (UserTableSeeder::class);
        $this->call (AreaTableSeeder::class);
        $this->call (TagTableSeeder::class);
        $this->call (DocTableSeeder::class);
        $this->call (PermissionTableSeeder::class);
        $this->call (RoleTableSeeder::class);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}

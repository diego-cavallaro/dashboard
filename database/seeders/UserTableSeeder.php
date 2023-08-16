<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::truncate();
        User::truncate();

        $adminRole =  Role::create(['name'=>'Administrator']);
        $userRole =  Role::create(['name'=>'User']);
            
            $admin = new User;  
                            
            $admin->name = 'administrador';
            $admin->nickName = 'admin';
            $admin->email =  'administrador@fscnet.com.ar';
            $admin->email_verified_at =  NOW() ;
            $admin->password = '$2y$10$nR1SB1dJoD5pL3WZlOqWBOiB75Z..1kfdzikH1iNjH2xyMmum84K.';
            $admin->save();

            $admin->assignRole($adminRole);

            $users = new User;  
                            
            $users->name = 'user1';
            $users->nickName = 'user1';
            $users->email =  'd.cavallaro@fscnet.com.ar';
            $users->email_verified_at =  NOW() ;
            $users->password = bcrypt('123123');
            $users->save();

            $users->assignRole($userRole);
    }
}
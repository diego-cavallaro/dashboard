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
        User::truncate();

            $admin = new User;  
                            
            $admin->name = 'administrador';
            $admin->nickName = 'admin';
            $admin->email =  'administrador@fscnet.com.ar';
            $admin->email_verified_at =  NOW() ;
            $admin->password = bcrypt('dashboard');;
            $admin->legajo = '1';
            $admin->enable = true;
            $admin->save();


            $users = new User;  
                            
            $users->name = 'Test1';
            $users->nickName = 'User1';
            $users->email =  'd.cavallaro@fscnet.com.ar';
            $users->email_verified_at =  NOW() ;
            $users->password = bcrypt('dashboard');
            $users->legajo = 123;
            $users->enable = true;
            $users->save();
    }
}
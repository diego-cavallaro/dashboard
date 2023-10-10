<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::truncate();

        //Site Level
        $siteAdminRole  =  Role::create(['name'=>'siteAdminRole']);
        $siteUserRole   =  Role::create(['name'=>'siteUserRole']);

        //Docs Level
        $docAdminRole   =  Role::create(['name'=>'docAdminRole']);
        $docUserRole    =  Role::create(['name'=>'docUserRole']);
        $docViewRole    =  Role::create(['name'=>'docViewRole']);
        
        
        $siteAdminRole  ->save();
        $siteUserRole   ->save();
        $docAdminRole   ->save();
        $docUserRole    ->save();
        $docViewRole    ->save();

        $user = \App\Models\User::first();
        $user->assignRole($siteAdminRole);
        $user = \App\Models\User::find(2);
        $user->assignRole($siteUserRole);


        //$siteAdminRole->givePermissionTo('docAdmin');

        $siteUserRole->givePermissionTo('login','viewProfile');

        $docUserRole ->givePermissionTo('docView','docCreate','docDelete');
        $docViewRole ->givePermissionTo('docView');
    }
}

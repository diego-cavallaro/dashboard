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
        $siteAdminRole  =  Role::create(['name'=>'siteAdminRole', 'description'=>'Administrador Global del Sitio']);
        $siteUserRole   =  Role::create(['name'=>'siteUserRole','description'=>'Usuario Global del Sitio']);

        //Docs Level
        //$docAdminRole   =  Role::create(['name'=>'docAdminRole','description'=>'Administrador de documentos']);
        $docUserRole    =  Role::create(['name'=>'docUserRole','description'=>'Creador de documentos']);
        //$docViewRole    =  Role::create(['name'=>'docViewRole','description'=>'Lector de documentos']);
        
        //Certificados Level
        $certUserRole    =  Role::create(['name'=>'certUserRole','description'=>'Creador de certificados']);
        
        //Mecanizado Level
        $mecUserRole    =  Role::create(['name'=>'mecUserRole','description'=>'Usuario de Mecanizado']);
        
        $siteAdminRole  ->save();
        $siteUserRole   ->save();
        $docUserRole    ->save();
        $certUserRole   ->save();
        $mecUserRole    ->save();

        $user = \App\Models\User::first();
        $user->assignRole($siteAdminRole);
        $user = \App\Models\User::find(2);
        $user->assignRole($siteUserRole);


        //$siteAdminRole->givePermissionTo('docAdmin');

        $siteUserRole->givePermissionTo('login','viewProfile', 'docView');

        $docUserRole ->givePermissionTo('docView','docCreate','docDelete');
    }
}

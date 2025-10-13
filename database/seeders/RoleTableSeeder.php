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
        $ppcpUserRole   =  Role::create(['name'=>'ppcpUserRole','description'=>'Usuario PPCP']);
        $calidadUserRole   =  Role::create(['name'=>'calidadUserRole','description'=>'Usuario Calidad']);

        //Docs Level
        //$docAdminRole   =  Role::create(['name'=>'docAdminRole','description'=>'Administrador de documentos']);
        //$docUserRole    =  Role::create(['name'=>'docUserRole','description'=>'Creador de documentos']);
        //$docViewRole    =  Role::create(['name'=>'docViewRole','description'=>'Lector de documentos']);
        
        //Certificados Level
        //$certUserRole    =  Role::create(['name'=>'certUserRole','description'=>'Creador de certificados']);
        
        //Mecanizado Level
        //$mecUserRole    =  Role::create(['name'=>'mecUserRole','description'=>'Usuario de Mecanizado']);
        //
        $siteAdminRole  ->save();
        $siteUserRole   ->save();
        $ppcpUserRole   ->save();
        $calidadUserRole   ->save();
        //$docUserRole    ->save();
        //$certUserRole   ->save();
        //$mecUserRole    ->save();
//
        $user = \App\Models\User::first();
        $user->assignRole($siteAdminRole);

        $siteAdminRole->givePermissionTo('admin');
        $siteAdminRole->givePermissionTo('user');
        $siteAdminRole->givePermissionTo('ppcpUser');
        $siteAdminRole->givePermissionTo('calidadUser');

        
        $user = \App\Models\User::find(2);
        $user->assignRole($siteUserRole);
        
        $siteUserRole->givePermissionTo('user');

        //$siteUserRole->givePermissionTo('login','viewProfile', 'docView');

        //$docUserRole ->givePermissionTo('docView','docCreate','docDelete');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::truncate();
        
        $siteUser       =  Permission::create(['name'=>'login']);
        $viewProfile    =  Permission::create(['name'=>'viewProfile']);
        $docView        =  Permission::create(['name'=>'docView']);
        $docCreate      =  Permission::create(['name'=>'docCreate']);
        $docDelete      =  Permission::create(['name'=>'docDelete']);

        $siteUser       ->save();
        $viewProfile    ->save();
        $docView        ->save();
        $docCreate      ->save();
        $docDelete      ->save();
    }
}

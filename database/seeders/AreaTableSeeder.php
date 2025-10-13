<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Area;

class AreaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Area::truncate();
            $area = new area;
            $area->name = 'Sistemas';
            $area->description = 'Sistemas';
            $area->save();

            $area = new area;
            $area->name = 'RRHH';
            $area->description = 'Recursos humanos';
            $area->save();

            $area = new area;
            $area->name = 'PPyCP';
            $area->description = 'Planificación programación y control de la producción';
            $area->save();

            $area = new area;
            $area->name = 'Administración';
            $area->description = 'Administracion y Finanzas';
            $area->save();

            $area = new area;
            $area->name = 'I+D';
            $area->description = 'Invetigacion y Desarrollo';
            $area->save();

            $area = new area;
            $area->name = 'Comercial';
            $area->description = 'Comercial y Ventas';
            $area->save();

            $area = new area;
            $area->name = 'Mantenimiento';
            $area->description = 'Mantenimiento';
            $area->save();

    }
}

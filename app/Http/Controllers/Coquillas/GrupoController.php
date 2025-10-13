<?php

namespace App\Http\Controllers\Coquillas;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Models\Coquillas\ShopResource;
use App\Models\Coquillas\CalendarWeek;
use App\Models\Coquillas\ShopResourceSite;
use App\Http\Requests\Coquillas\StoreGrupo;
use App\Http\Requests\Coquillas\UpdateGrupo;

class GrupoController extends Controller
{
    public function index()
    {
        //------------------------ Buscamos los Grupos de Coquillas -----------------------------
        $gruposCoquilla = ShopResource::where('TYPE', 'G')->where(function($query) {
            $query->where('ID', 'LIKE', 'GC%')
                  ->orWhere('ID', 'LIKE', 'GS%')
                  ->orWhere('ID', 'LIKE', 'GT%')
                  ->orWhere('ID', 'LIKE', 'GQ%')
                  ->orWhere('ID', 'LIKE', 'GE%');
        })->get();

       return view('Coquillas.grupos')->with(compact('gruposCoquilla'));
    }

    public function create()
    {
        //Llamamos a la vista
        return view('Coquillas.altaGrupo');
    }

    public function store(StoreGrupo $request)
    {
        DB::beginTransaction();
        try {
            $shopResource = new ShopResource();
            $shopResource->ID = $request->post('Grupo');
            $shopResource->DESCRIPTION = $request->post('Descripcion');
            $shopResource->SCHEDULE_NORMALLY = "Y";
            $shopResource->AUTO_REPORTING = "N";
            $shopResource->TYPE = "G";
            //Para "Programar Todo" = "I". Para "Programar Uno" = "X"
            if($request->post('programar') === "1")
            {
                $shopResource->EXCLUSIVITY = "X";
            }
            else
            {
                $shopResource->EXCLUSIVITY = "I";
            }
            $shopResource->save();

            //--------- Creamos el nuevo recurso asociado a la empresa FSC ---------
            $shopResourceSite = new ShopResourceSite();
            $shopResourceSite->SITE_ID = 'FSC';
            $shopResourceSite->RESOURCE_ID = $request->post('Grupo');
            $shopResourceSite->SHIFT_1_CAPACITY = 0;
            $shopResourceSite->SHIFT_2_CAPACITY = 0;
            $shopResourceSite->SHIFT_3_CAPACITY = 0;
            //Mandamos a Guardar la sociacion
            $shopResourceSite->save();

            //--------- Armamos la disponibilidad del recurso para la semana ---------
            for ($i = 0; $i < 7; $i++)
            {
                $calendarWeek = new CalendarWeek();
                $calendarWeek->RESOURCE_ID = $request->post('Grupo');
                $calendarWeek->DAY_OF_WEEK = $i;
                $calendarWeek->START_OF_DAY = Carbon::today()->format('Ymd');
                $calendarWeek->SHIFT_1 = 8;
                $calendarWeek->SHIFT_2 = 8;
                $calendarWeek->SHIFT_3 = 8;
                $calendarWeek->SITE_ID = 'FSC';
                //Mandamos a guardar la disponibilidad de la semana
                $calendarWeek->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->route('grupos.show')->with("error","Error al intentar grabar");
        }

       return redirect()->route('grupos.show')->with("success","Agregado con éxito");
    }

    public function edit($resoureId)
    {
        //------------------------------ Buscamos el Grupo -----------------------------------
        $grupo = ShopResource::find($resoureId);
        //Llamamos a la vista
        return view('Coquillas.edicionGrupo')->with(compact('grupo'));
    }

    public function update(UpdateGrupo $request)
    {
        DB::beginTransaction();
        try {
            $resoureId = $request->post('resourceId');

            $shopResource = ShopResource::find($resoureId);
            $shopResource->DESCRIPTION = $request->post('Descripcion');
            //Para "Programar Todo" = "I". Para "Programar Uno" = "X"
            if($request->post('programar') === "1")
            {
                $shopResource->EXCLUSIVITY = "X";
            }
            else
            {
                $shopResource->EXCLUSIVITY = "I";
            }
            $shopResource->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->route('grupos.show')->with("error","Error al intentar grabar");
        }

        return redirect()->route('grupos.show')->with("success","Modificado con éxito");
    }
}
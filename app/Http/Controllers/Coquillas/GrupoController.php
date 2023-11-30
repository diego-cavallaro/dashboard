<?php

namespace App\Http\Controllers\Coquillas;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Models\Coquillas\ShopResource;
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
            //Averiguar esto porque: Para "Programar Todo" = "I". Para "Programar Uno" = "X"
            $shopResource->EXCLUSIVITY = "X";
            $shopResource->save();
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
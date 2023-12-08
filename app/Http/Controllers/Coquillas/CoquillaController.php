<?php

namespace App\Http\Controllers\Coquillas;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Models\Coquillas\Coquilla;
use App\Models\Coquillas\EstadoCoquilla;
use App\Models\Coquillas\ShopResource;
use App\Models\Coquillas\ShopGroup;
use App\Models\Coquillas\CalendarWeek;
use App\Models\Coquillas\CalendarChange;
use App\Models\Coquillas\ShopResourceSite;

use App\Http\Requests\Coquillas\StoreCoquilla;
use App\Http\Requests\Coquillas\UpdateCoquilla;

class CoquillaController extends Controller
{
    public function index()
    {
       $coquillas=Coquilla::with('EstadoCoquilla')->orderBy('RESOURCE_ID', 'asc')->get();

       //-----------------------------------------------------------------
       $estadosCoquilla = EstadoCoquilla::orderBy('ID', 'asc')->get();
       //-----------------------------------------------------------------

       return view('Coquillas.coquillas')->with(compact('coquillas'))
                                         ->with(compact('estadosCoquilla'))
                                         ->with('conAgujero', 0)
                                         ->with('conCanal', 0)
                                         ->with('estadoCoquilla', 0);
    }

    public function filter(Request $request)
    {
        $conAgujero = 0;
        $conCanal = 0;
        $estadoCoquilla = 0;
        $queryCoquilla = Coquilla::query();

        // dd($request->all());
        //-----------------------------------------------------------------
        if( $request->post('ConAgujero') != null){
            $conAgujero = 1;
        };
        $queryCoquilla->when($conAgujero == 1, function($query){
            return $query->where('CON_AGUJERO', 1);
        });
        //-----------------------------------------------------------------
        if( $request->post('ConCanal') != null){
            $conCanal = 1;
        };
        $queryCoquilla->when($conCanal == 1, function($query){
            return $query->where('CON_CANAL', 1);
        });
        //-----------------------------------------------------------------
        if( $request->post('EstadoCoquilla') != 0){
            $estadoCoquilla = $request->post('EstadoCoquilla');
            $queryCoquilla->where('ESTADO_COQUILLA_ID', $estadoCoquilla);
        };
        //-----------------------------------------------------------------
        
        $coquillas = $queryCoquilla->get();

        //-----------------------------------------------------------------
        $estadosCoquilla = EstadoCoquilla::orderBy('ID', 'asc')->get();
        //-----------------------------------------------------------------

        return view('Coquillas.coquillas')->with(compact('coquillas'))
                                        ->with(compact('estadosCoquilla'))
                                        ->with('conAgujero', $conAgujero)
                                        ->with('conCanal', $conCanal)
                                        ->with('estadoCoquilla', $estadoCoquilla);
    }

    public function create()
    {
        //-----------------------------------------------------------------
        $estadosCoquilla = EstadoCoquilla::orderBy('ID', 'asc')->get();
        //-----------------------------------------------------------------
        //Llamamos a la vista
        return view('Coquillas.altaCoquilla')->with(compact('estadosCoquilla'));
    }

    public function store(StoreCoquilla $request)
    {
        //dd($request->all());
        DB::beginTransaction();
        try {

            $coquilla = new Coquilla();

            $coquilla->RESOURCE_ID = $request->post('Coquilla');
            $coquilla->DIAMETRO1 = $request->post('Diametro1');
            $coquilla->DIAMETRO2 = $request->post('Diametro2');
            $coquilla->ALTURA = $request->post('Altura');
            $coquilla->ESTADO_COQUILLA_ID = $request->post('EstadoCoquilla');
            $coquilla->OBSERVACIONES =  $request->post('Observaciones');
            $coquilla->FECHA = Carbon::parse($request->post('Fecha'))->format('Ymd'); //Fecha Disponibilidad
            $coquilla->CON_CANAL = ($request->input('ConCanal') == "on" ? 1 : 0);
            $coquilla->CON_AGUJERO = ($request->input('ConAgujero') == "on" ? 1 : 0);
            if($request->post('Pista1') != null)
            {
                $coquilla->PISTA1 = $request->post('Pista1');
            }
            if($request->post('Pista2') != null)
            {
                $coquilla->PISTA2 = $request->post('Pista2');
            }
            if($request->post('Pista1') != null | $request->post('Pista2') != null)
            {
                $coquilla->FECHA_EDICION_PISTA =  Carbon::now()->format('Ymd H:i:s');
            }
            //Mandamos a guardar la nueva coquilla
            $coquilla->save();

            //--------- Creamos el nuevo recurso para Visual Manufacturing ---------
            $shopResource = new ShopResource();
            $shopResource->ID = $request->post('Coquilla');
            $shopResource->DESCRIPTION = $request->post('Observaciones');
            $shopResource->SCHEDULE_TYPE = 0;
            $shopResource->SCHEDULE_NORMALLY = "Y";
            $shopResource->AUTO_REPORTING = "N";
            $shopResource->TYPE = "I";
            $shopResource->EXCLUSIVITY = "X";
            $shopResource->SHIFT_1_CAPACITY = 1;
            $shopResource->SHIFT_2_CAPACITY = 1;
            $shopResource->SHIFT_3_CAPACITY = 1;
            //----------------- Campos USER ------------------
            $shopResource->USER_1 = $request->post('Diametro1');
            $shopResource->USER_2 = $request->post('Diametro2');
            $shopResource->USER_3 = $request->post('Altura');
            //-------
            $estadoCoquilla = EstadoCoquilla::find($request->post('EstadoCoquilla'));
            $shopResource->USER_4 = $estadoCoquilla->DESCRIPCION;
            //-------
            $shopResource->USER_5 = Carbon::parse($request->post('Fecha'))->format('Ymd');
            $shopResource->USER_6 = ($request->input('ConCanal') == "on" ? "Si" : "No");
            $shopResource->USER_7 = ($request->input('ConAgujero') == "on" ? "Si" : "No");
            //Mandamos a guardar el nuevo recurso a Visual
            $shopResource->save();

            //--------- Armamos la disponibilidad del recurso para la semana ---------
            for ($i = 0; $i < 7; $i++)
            {
                $calendarWeek = new CalendarWeek();
                $calendarWeek->RESOURCE_ID = $request->post('Coquilla');
                $calendarWeek->DAY_OF_WEEK = $i;
                $calendarWeek->START_OF_DAY = Carbon::today()->format('Ymd');
                $calendarWeek->SHIFT_1 = 8;
                $calendarWeek->SHIFT_2 = 8;
                $calendarWeek->SHIFT_3 = 8;
                $calendarWeek->SITE_ID = 'FSC';
                //Mandamos a guardar la disponibilidad de la semana
                $calendarWeek->save();
            }

            //--------- Armamos la excepción de calendario de NO disponibilidad ---------
            if(Carbon::parse($request->post('Fecha'))->format('Ymd') > Carbon::now()->format('Ymd H:i:s'))
            {
                $calendarChange = new CalendarChange();
                $calendarChange->SCHEDULE_ID = null;
                $calendarChange->RESOURCE_ID = $request->post('Coquilla');
                $calendarChange->START_DATE = Carbon::today()->format('Ymd');
                $calendarChange->END_DATE = Carbon::parse($request->post('Fecha'))->format('Ymd');
                $calendarChange->START_OF_DAY = Carbon::today()->format('Ymd');
                $calendarChange->SHIFT_1 = 0;
                $calendarChange->SHIFT_2 = 0;
                $calendarChange->SHIFT_3 = 0;
                $calendarChange->SHIFT_1_CAPACITY = 0;
                $calendarChange->SHIFT_2_CAPACITY = 0;
                $calendarChange->SHIFT_3_CAPACITY = 0;
                $calendarChange->SITE_ID = 'FSC';
                //Mandamos a guardar la excepcion de calendario
                $calendarChange->save();
            }

            //--------- Creamos el nuevo recurso asociado a la empresa FSC ---------
            $shopResourceSite = new ShopResourceSite();
            $shopResourceSite->SITE_ID = 'FSC';
            $shopResourceSite->RESOURCE_ID = $request->post('Coquilla');
            $shopResourceSite->SHIFT_1_CAPACITY = 1;
            $shopResourceSite->SHIFT_2_CAPACITY = 1;
            $shopResourceSite->SHIFT_3_CAPACITY = 1;
            //Mandamos a Guardar la sociacion
            $shopResourceSite->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e);
            return redirect()->route('coquillas.show')->with("error","Error al intentar grabar");
        }
        return redirect()->route('coquillas.show')->with("success","Agregado con éxito");
    }

    public function edit($resoureId)
    {
        //------------------------------ Buscamos la Coquilla -----------------------------------
        $coquilla = Coquilla::find($resoureId);
        //------------------------------- Buscamos sus Grupos -----------------------------------
        $shopGroups = ShopGroup::where('SUB_RESOURCE_ID', $resoureId)->get();
        //------------------------ Buscamos los Estados de Coquilla -----------------------------
        $estadosCoquilla = EstadoCoquilla::orderBy('ID', 'asc')->get();
        //------------------------ Buscamos los Grupos de Coquillas -----------------------------
        $gruposCoquilla = ShopResource::where('TYPE', 'G')->where(function($query) {
			    $query->where('ID', 'LIKE', 'GC%')
					  ->orWhere('ID', 'LIKE', 'GS%')
                      ->orWhere('ID', 'LIKE', 'GT%')
                      ->orWhere('ID', 'LIKE', 'GQ%')
                      ->orWhere('ID', 'LIKE', 'GE%');
            })->get();
        //---------------------------------------------------------------------------------------
        
        //Llamamos a la vista
        return view('Coquillas.edicionCoquilla')->with(compact('coquilla'))
                                                ->with(compact('shopGroups'))
                                                ->with(compact('estadosCoquilla'))
                                                ->with(compact('gruposCoquilla'));
    }

    public function update(UpdateCoquilla $request)
    {
        DB::beginTransaction();
        try 
        {
            $resoureId = $request->post('resourceId');
            $coquilla = Coquilla::find($resoureId);
    
            $coquilla->DIAMETRO1 = $request->post('Diametro1');
            $coquilla->DIAMETRO2 = $request->post('Diametro2');
            if($request->post('EstadoCoquilla') !== '0')
            {
                $coquilla->ESTADO_COQUILLA_ID = $request->post('EstadoCoquilla');
            }
            $coquilla->ALTURA = $request->post('Altura');
            $coquilla->OBSERVACIONES =  $request->post('Observaciones');
            //Para utilizar mas adelante
            $fechaBase = Carbon::parse($coquilla->FECHA)->format('Ymd');
            //--------------------------
            $coquilla->FECHA = Carbon::parse($request->post('Fecha'))->format('Ymd');
            $coquilla->CON_CANAL = ($request->input('ConCanal') === "on" ? 1 : 0);
            $coquilla->CON_AGUJERO = ($request->input('ConAgujero') === "on" ? 1 : 0);
            if($request->post('Pista1') != $coquilla->PISTA1 | $request->post('Pista2') != $coquilla->PISTA2)
            {
                $coquilla->FECHA_EDICION_PISTA =  Carbon::now()->format('Ymd H:i:s');
            }            
            $coquilla->PISTA1 = $request->post('Pista1');
            $coquilla->PISTA2 = $request->post('Pista2');

            $coquilla->save();

            //Actualizamos el recurso en Visual Manufacturing
            $shopResource = ShopResource::find($resoureId);
            $shopResource->DESCRIPTION = $request->post('Observaciones');
            //----------------- Campos USER ------------------
            $shopResource->USER_1 = $request->post('Diametro1');
            $shopResource->USER_2 = $request->post('Diametro2');
            $shopResource->USER_3 = $request->post('Altura');
            //-------
            if($request->post('EstadoCoquilla') !== '0')
            {
                $estadoCoquilla = EstadoCoquilla::find($request->post('EstadoCoquilla'));
                $shopResource->USER_4 = $estadoCoquilla->DESCRIPCION;
            }
            //-------
            $shopResource->USER_5 = Carbon::parse($request->post('Fecha'))->format('Ymd');
            $shopResource->USER_6 = ($request->input('ConCanal') == "on" ? "Si" : "No");
            $shopResource->USER_7 = ($request->input('ConAgujero') == "on" ? "Si" : "No");
            $shopResource->save();

            $calendarChange = CalendarChange::where('RESOURCE_ID', $request->post('resourceId'))->first();
            //Si cambiaron la fecha de disponibilidad, aplicamos eso para la programación
            if(Carbon::parse($request->post('Fecha'))->format('Ymd') !== $fechaBase)
            {
                $calendarChange->START_DATE = Carbon::today()->format('Ymd');
                $calendarChange->END_DATE = Carbon::parse($request->post('Fecha'))->format('Ymd');
                $calendarChange->START_OF_DAY = Carbon::today()->format('Ymd');
                //Mandamos a guardar la excepcion de calendario
                $calendarChange->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->route('coquillas.show')->with("error","Error al intentar grabar");
        }
        return redirect()->route('coquillas.show')->with("success","Agregado con éxito");
    }

    public function storeGrupo($coquilla, $grupo)
    {
        DB::beginTransaction();
        try 
        {
            //Obtenemos el maximo Sequancial del Grupo seleccionado y le sumamos 10
            $seqNo = ShopGroup::where('GROUP_RESOURCE_ID', $grupo)->max('SEQ_NO');
            if($seqNo == null)
            {
                $seqNo = 10;
            }
            else
            {
                $seqNo = $seqNo + 10;
            }

            $shopGroup = new ShopGroup();
            $shopGroup->GROUP_RESOURCE_ID = $grupo;
            $shopGroup->SEQ_NO = $seqNo;
            $shopGroup->SUB_RESOURCE_ID = $coquilla;
            $shopGroup->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e);
            return redirect()->route('coquillas.edit')->with("error","Error al intentar grabar");
        }
        
        return redirect()->route('coquillas.edit', $coquilla);
    }

    public function destroyGrupo($coquilla, $grupo)
    {
        DB::beginTransaction();
        try 
        {
            // dd($coquilla);
            $shopGroup = ShopGroup::where('GROUP_RESOURCE_ID', $grupo)
                                    ->  where('SUB_RESOURCE_ID', $coquilla)->first();

            // dd($shopGroup);
            $shopGroup->delete(); //returns true/false

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->route('coquillas.edit')->with("error","Error al intentar grabar");
        }
        
        return redirect()->route('coquillas.edit', $coquilla);
    }
}
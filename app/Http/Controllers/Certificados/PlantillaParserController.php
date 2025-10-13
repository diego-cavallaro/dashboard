<?php

namespace App\Http\Controllers\Certificados;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use App\Models\Certificados\Hr;
use App\Models\Certificados\Plantilla;
use App\Models\Certificados\Certificado;
use App\Models\Certificados\CertificadoDet;
use App\Models\Certificados\CertificadoConfig;
use App\Models\Certificados\CertificadoConfigDet;
use App\Models\Certificados\ConsultaPiezasAsociadas;

class PlantillaParserController extends Controller
{
    public function index($tipoCertificadoId)
    {
        $hrList = ConsultaPiezasAsociadas::where('TIPO_CERTIFICADO_ID',  $tipoCertificadoId)
                                           ->where('VB', 0)->get();

        return view('Certificados.piezaSinCertificado')->with(compact('hrList'))
                                                       ->with('tipoCertificadoId', $tipoCertificadoId)
                                                       ->with('conVb', 0);
    }

    public function filter(Request $request)
    {
        $tipoCertificadoId = $request->post('tipoCertificadoId');
        $conVb = 0;

        // dd($request->all());
        // dd($request->post('VB') != null);

        if( $request->post('VB') != null){
            $conVb = 1;
        };

        $hrList = ConsultaPiezasAsociadas::where('TIPO_CERTIFICADO_ID',  $tipoCertificadoId)
                                           ->where('VB', $conVb)->get();

        return view('Certificados.piezaSinCertificado')->with(compact('hrList'))
                                                       ->with('tipoCertificadoId', $tipoCertificadoId)
                                                       ->with('conVb', $conVb);
    }

    public function create($nroPieza, $tipoCertificadoId)
    {
        $hr = Hr::where('PIEZA', $nroPieza)->first();

        //Obtenemos primero el Certificado Config de acuerdo al número de pieza y tipo de certificado
        $certificadoConfig = buscarCertificadoConfig($nroPieza, $tipoCertificadoId);

        $certificadoConfigId = $certificadoConfig->ID;

        //Obtenemos ahora la lista de variables (Carga Manual) a reemplazar en la plantilla
        $configDetsManuales = CertificadoConfigDet::where('CERTIFICADO_CONFIG_ID', $certificadoConfigId)
                      ->where('INGRESO_MANUAL', 1)->get();

        //Obtenemos ahora la lista de variables (Carga Automática) a reemplazar en la plantilla
        $configDetsAutomaticos = CertificadoConfigDet::where('CERTIFICADO_CONFIG_ID', $certificadoConfigId)
                      ->where('INGRESO_MANUAL', 0)->get();

        //Coleccion de lista de valores de los datos de Carga Automatica
        $paramCollection = collect([]);
        $paramCollection = armarListaParametrosAutomaticos($paramCollection, $nroPieza, $tipoCertificadoId);

        // dd($paramCollection);

        //Llamamos a la vista
        return view('Certificados.cargaManual')->with(compact('hr'))
                                               ->with(compact('configDetsManuales'))
                                               ->with(compact('configDetsAutomaticos'))
                                               ->with(compact('paramCollection'))
                                               ->with('tipoCertificadoId', $tipoCertificadoId);
    }

    public function store(Request $request)
    {
        //dd($request->all());

        $nroPieza = $request->post('nroPieza');
        $tipoCertificadoId = $request->post('tipoCertificadoId');
        //Obtenemos primero el Certificado Config de acuerdo al número de parte
        $certificadoConfig = buscarCertificadoConfig($nroPieza, $tipoCertificadoId);
        //Obtenemos los datos de la Plantilla asociada
        $plantilla = Plantilla::find($certificadoConfig->PLANTILLA_ID);

        DB::beginTransaction();
        try {

            $paramCollection = collect([]);
            //Obtenemos la lista de variables de la Plantilla (Carga Automática)
            $paramCollection = armarListaParametrosAutomaticos($paramCollection, $nroPieza, $tipoCertificadoId);
            //Obtenemos la lista de variables de la Plantilla (Carga Manual)
            $paramCollection = armarListaParametrosManuales($paramCollection, $nroPieza, $tipoCertificadoId);

            $certificado = new Certificado();
            $certificado->PIEZA = $nroPieza;
            $certificado->PART_ID = $request->post('nroParte');
            $certificado->CERTIFICADO_CONFIG_ID = $certificadoConfig->ID;
            $certificado->PLANO = $certificadoConfig->PLANO;
            // dd($plantilla->CUERPO);
            $certificado->CUERPO = $plantilla->CUERPO;
            // $certificado->VB = 0;
            $certificado->VB = ($request->input('VB') == "on" ? 1 : 0);
            if($request->post('VB') == "on")
            {
               $certificado->FECHA_VB = Carbon::now()->format('Ymd H:i:s');
            }
            $certificado->FECHA_INSERT = Carbon::now()->format('Ymd H:i:s');
            
            //Mandamos a guardar la cabecera
            $certificado->save();

            $numeroCertificado = "";
            //Vamos a tener que recorrer el array con las variables y de ahí
            //sacar los valores que trajo el Request con "_"
            //Aquí hacemos efectivo el reemplazo de variables por los datos extraidos del POST
            foreach($paramCollection as $param)
            {
                $valor = null;

                if(Arr::get($param, 'Parametro') == 'NumeroCertificado')
                {
                    $numeroCertificado = Arr::get($param, 'Valor');
                }
                //Si estamos ante los Option Buttons (4 = Boolean)
                if(Arr::get($param, 'TipoDato') == 4)
                {
                    $referencia = Arr::get($param, 'Parametro');
                    $name = substr($referencia, 0, strpos($referencia,"."));
                    $value = substr($referencia, strpos($referencia,".") + 1);
                    //Si se cumple que el valor elegido es igual al parámetro que estamos revisando
                    //Por ejemplo si en el POST llega: "Dureza" == "Shore"
                    if($request->post($name) == $value)
                       $valor = "true";
                    else
                       $valor = "false";
                } //Si estamos ante la carga de archivos (5 = Imagen)
                else if(Arr::get($param, 'TipoDato') == 5)
                {
                    //Ejemplo: 'C:\laragon\www\dashboard\public\Certificados\Ensayos\'
                    $carpeta = Storage::disk('ensayos')->path('');
                    //dd($carpeta);
                    //Armamos las carpetas (de no existir) para la subida de archivos (año/mes/numeroCertificado)
                    $anio = Carbon::now()->year;
                    $mes = Carbon::now()->format('m');
                    if (!file_exists($carpeta.$anio)) {
                        mkdir($carpeta.$anio, 0777, true);
                    }
                    if (!file_exists($carpeta.$anio.'/'.$mes)) {
                        mkdir($carpeta.$anio.'/'.$mes, 0777, true);
                    }
                    if (!file_exists($carpeta.$anio.'/'.$mes.'/'.$numeroCertificado)) {
                        mkdir($carpeta.$anio.'/'.$mes.'/'.$numeroCertificado, 0777, true);
                    }
                
                    $referencia = Arr::get($param, 'Parametro');
                    if($request[$referencia] != null)
                    {
                        $nombreArchivo = $request[$referencia]->getClientOriginalName();

                        //Ejemplo: '2023\08\20230822154474\'
                        //$subCarpeta = Str::replace("'", "", $anio."'\'".$mes."'\'".$numeroCertificado."'\'");
                        $subCarpeta = $anio.'/'.$mes.'/'.$numeroCertificado.'/';
                        //Movemos el archivo a la carpeta destino
                        $request[$referencia]->move($carpeta.$subCarpeta, $nombreArchivo);
                        //Ejemplo: '2023/08/20230822154474/MiImagen.jpg'
                        $valor = $anio."/".$mes."/".$numeroCertificado."/".$nombreArchivo;
                    }
                }
                else
                {
                    //Obtenemos el Array de Parametro->Valor y lo adaptamos al nombre que recibimos por POST
                    $paramPostArray = Str::replace(".", "_", $param);
                    //Obtenemos el Nombre del parámetro para obtener despues el valor en el POST
                    $paramPost = Arr::get($paramPostArray, 'Parametro');
                    $valor = $request->post($paramPost);
                }
                //Creamos la instancia que va a formar parte de la lista de parámetros
                $certificadoDet = new CertificadoDet();
                //Agregamos la referencia al ID del Certificado Creado
                $certificadoDet->CERTIFICADO_ID = $certificado->ID;
                //Obtenemos el Parámetro original que viene de las configuraciones
                $certificadoDet->REFERENCIA = Arr::get($param, 'Parametro');
                //Ahora sí accesamos al valor que trajo el POST y lo asignamos a la entidad
                $certificadoDet->VALOR = $valor;
                $certificadoDet->CERTIFICADO_CONFIG_DET_ID = Arr::get($param, 'Id');
                //Mandamos a grabar los datos de la variable
                $certificadoDet->save();
                $certificadoDet->Certificado()->associate($certificado);
            }

            DB::commit();
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
            return redirect()->route('certificados.show', $tipoCertificadoId)->with("error","Error al intentar grabar");
        }
        if($request->input('VB') == "on")
           return redirect()->route('certificados.vistaPreliminar', $certificado->ID)->with("success","Agregado con éxito");
        else
           return redirect()->route('certificados.show', $tipoCertificadoId)->with("success","Agregado con éxito");
    }

    public function edit($certificadoId)
    {
        $certificado = Certificado::find($certificadoId);
        $certificadoDet = CertificadoDet::where('CERTIFICADO_ID', $certificadoId)->get();
        $tipoCertificadoId = $certificado->CertificadoConfig->Plantilla->TIPO_CERTIFICADO_ID;

        //Llamamos a la vista
        return view('Certificados.edicionManual')->with(compact('certificado'))
                                                 ->with(compact('certificadoDet'))
                                                 ->with('tipoCertificadoId', $tipoCertificadoId);
    }

    public function update(Request $request)
    {
        //dd($request->all());

        DB::beginTransaction();
        try 
        {
            $certificadoId = $request->post('certificadoId');
            $tipoCertificadoId = $request->post('tipoCertificadoId');

            $certificado = Certificado::find($certificadoId);
            $certificado->VB = ($request->input('VB') == "on" ? 1 : 0) ;
            if($request->post('VB') == "on")
            {
                // dd(Carbon::now()->format('Ymd H:i:s'));
               $certificado->FECHA_VB = Carbon::now()->format('Ymd H:i:s');
            }
            else
            {
               $certificado->FECHA_VB = null;
            }
            $certificado->save();

            $nroPieza = $certificado->PIEZA;
            $fechaInsert = new Carbon($certificado->FECHA_INSERT);

            $paramCollection = collect([]);
            //Obtenemos la lista de variables de la Plantilla (Carga Automática)
            $paramCollection = armarListaParametrosAutomaticos($paramCollection, $nroPieza, $tipoCertificadoId);
            //Obtenemos la lista de variables de la Plantilla (Carga Manual)
            $paramCollection = armarListaParametrosManuales($paramCollection, $nroPieza, $tipoCertificadoId);

            foreach($paramCollection as $param)
            {
                $referencia = Arr::get($param, 'Parametro');
                $valor = null;

                if(Arr::get($param, 'Parametro') == 'NumeroCertificado')
                {
                    $numeroCertificado = Arr::get($param, 'Valor');
                }

                //Si estamos ante los Option Buttons (4 = Boolean)
                if(Arr::get($param, 'TipoDato') == 4)
                {
                    $name = substr($referencia, 0, strpos($referencia,"."));
                    $value = substr($referencia, strpos($referencia,".") + 1);
                    //Si se cumple que el valor elegido es igual al parámetro que estamos revisando
                    //Por ejemplo si en el POST llega: "Dureza" == "Shore"
                    if($request->post($name) == $value)
                       $valor = "true";
                    else
                       $valor = "false";
                } //Si estamos ante la carga de archivos (5 = Imagen)
                else if(Arr::get($param, 'TipoDato') == 5)
                {
                    //Si hemos subido un archivo nuevo en el Input File correspondiente
                    if($request->hasfile($referencia))
                    {
                        $file = $request->file($referencia);
                        $archivoNuevo = $file->getClientOriginalName();

                        //Obtenemos el valor del archivo original que tenemos apuntado en la base
                        $certificadoDet = CertificadoDet::where('CERTIFICADO_ID', $certificadoId)
                            ->where('REFERENCIA', $referencia)->first();
                        $rutaArchivoOriginal = $certificadoDet->VALOR;

                        //Ejemplo: 'C:\laragon\www\dashboard\public\Certificados\Ensayos\'
                        $carpeta = Storage::disk('ensayos')->path('');
                        $archivoOriginal = basename($rutaArchivoOriginal);

                        //SOLO si el nombre del archivo subido es distinto al que tenia guardado
                        if($archivoNuevo != $archivoOriginal)
                        {
                            //SOLO si el archivo Original trajo valor y si existe en carpeta 
                            if($rutaArchivoOriginal != null && File::exists($carpeta.$rutaArchivoOriginal))
                            {
                                //Eliminamos el archivo Original (antiguo)
                                File::delete($carpeta.$rutaArchivoOriginal);
                            }

                            //Obtenemos la carpeta del archivo original
                            if($rutaArchivoOriginal != null && $rutaArchivoOriginal != "")
                            {
                               $subCarpeta = Str::replace($archivoOriginal, "", $rutaArchivoOriginal);
                            }
                            else
                            {
                                $anio = $fechaInsert->year;
                                $mes = $fechaInsert->format('m');
                                //Ejemplo: '2023\08\20230822154474\'
                                $subCarpeta = $anio."\\".$mes."\\".$numeroCertificado."\\";
                            }

                            //Movemos el archivo a la carpeta destino
                            $request[$referencia]->move($carpeta.$subCarpeta, $archivoNuevo);

                            $valor = $subCarpeta.$archivoNuevo;
                        }
                    }
                    else
                    {
                        $valor = null;
                    }
                }
                else
                {
                    //Obtenemos el Array de Parametro->Valor y lo adaptamos al nombre que recibimos por POST
                    $paramPostArray = Str::replace(".", "_", $param);
                    //Obtenemos el Nombre del parámetro para obtener despues el valor en el POST
                    $paramPost = Arr::get($paramPostArray, 'Parametro');
                    $valor = $request->post($paramPost);
                }                
                
                //Actualizamos SOLO si tenemos un valor con que actualizar
                if($valor != null)
                {
                    $certigicadoDetModif = CertificadoDet::where('CERTIFICADO_ID', $certificadoId)
                                    ->where('REFERENCIA', $referencia)->first();

                    $certigicadoDetModif->VALOR = $valor;

                    $certigicadoDetModif->save();
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
            return redirect()->route('certificados.show', $tipoCertificadoId)->with("error","Error al intentar grabar");
        }

        if($request->input('VB') == "on")
           return redirect()->route('certificados.vistaPreliminar', $certificado->ID)->with("success","Agregado con éxito");
        else
           return redirect()->route('certificados.show', $tipoCertificadoId)->with("success","Agregado con éxito");
    }

    public function vistaPreliminar($certificadoId)
    {
        $modeloHtml = procesarPlantilla($certificadoId);

        // dd($modeloHtml);

        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($modeloHtml);
        $pdf->render();
        return $pdf->stream();
    }

    public function show($Id)
    {
        // print_r($Id);
        // $planillaControl = PlanillaControl::find($Id);
        // return view('planillasControlEliminar', compact('planillaControl'));
    }

    public function destroy($Id)
    {
        // $planillaControl = PlanillaControl::find($Id);
        // $planillaControl->delete();

        // return redirect()->route('control', $planillaControl->PlanillaId)->with("success","Eliminado con éxito");
    }
}
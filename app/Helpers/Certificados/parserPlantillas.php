<?php

use App\Models\Certificados\Certificado;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Certificados\Hr;
use App\Models\Certificados\CertificadoDet;
use App\Models\Certificados\CertificadoConfig;
use App\Models\Certificados\CertificadoConfigDet;

function buscarCertificadoConfig($piezaNro, $tipoCertificadoId)
{
    //Con eso obtenemos el Numero de Parte en HR (Hoja de Ruta)
    $hojaRuta = Hr::find($piezaNro);
    //Asignamos la variable
    $nroParte = $hojaRuta->CODIGO;

    //Obtenemos primero el Certificado Config de acuerdo al número de parte y tipo de certificado
    $config = CertificadoConfig::where('PART_ID', $nroParte)
    ->whereHas('Plantilla', function($query) use($tipoCertificadoId)
     {
         return $query->where('TIPO_CERTIFICADO_ID', '=', $tipoCertificadoId);
    })->get()[0];

    return $config;
}

function armarListaParametrosAutomaticos($paramCollection, $piezaNro, $tipoCertificadoId)
{
    //Para la busqueda de la Orden de Compra
    $ordenCompraId = '';
    //Para la Búsqueda de datos del Cliente
    $clienteId = '';

    //Obtenemos primero el Certificado Config de acuerdo al número de parte
    $config = buscarCertificadoConfig($piezaNro, $tipoCertificadoId);
    //Obtenemos ahora la lista de variables a reemplazar en la plantilla
    $configDets = CertificadoConfigDet::where('CERTIFICADO_CONFIG_ID', $config->ID)
                    ->where('INGRESO_MANUAL', 0)->get();

    //Obtenemos la lista de tablas a las que va a haber que consultar
    $tablasParametros = DB::connection('ProcesoPlanta')->table('CERT_CONFIG_DET_TABLAS')
                        ->orderBy('TABLA', 'desc')->get();

    //Recorremos la lista de tablas para ir armando los SELECTs y llenar valores
    foreach($tablasParametros as $tablaParametros)
    {
        //Valores para aplicar la busqueda en el SELECT final
        $campoBusqueda = "";
        $valorBusqueda = "";
        //Obtenemos la lista de campos a seleccionar en cada tabla
        $tabla = ((string) $tablaParametros->TABLA);
        
        //Definimos las claves para la busqueda de datos
        if($tabla == 'CUSTOMER_ORDER' || $tabla == 'CUSTOMER')
        {
            $campoBusqueda = "id";
            if($tabla == 'CUSTOMER_ORDER')
            {
                // dd($ordenCompraId);
                $valorBusqueda = $ordenCompraId;
            }
            else
            {
                $valorBusqueda = $clienteId;
            }
        }
        else
        {
            $campoBusqueda = "pieza";
            $valorBusqueda = $piezaNro;
        }
        //Obtenemos la lista de parametros que vamos a obtener de la TABLA
        $fileredConfigDets = $configDets->reject(function($value) use ($tabla){
                return !Str::contains((string)$value->REFERENCIA, $tabla.".");
        });
        //Armamos la lista de campos para la consulta
        $listaCampos = "";
        foreach($fileredConfigDets as $fileredConfigDet)
        {
            //Acá deberémos aplicar el FORMATO
            $listaCampos = $listaCampos.(Str::length($listaCampos) > 0 ? ", " : "").$fileredConfigDet->REFERENCIA;
        }

        //Ejecutamos SOLO si encontramos campos configurados para la tabla
        if(Str::length($listaCampos) > 0)
        {
            //Ejecutamos el SELECT resultante
            $listaValores = DB::connection('ProcesoPlanta')->table($tabla)
            ->select(DB::raw($listaCampos))
            ->where($campoBusqueda, $valorBusqueda)
            ->get()->toArray();
        }

        //Vamos cargando entonces la coleccion con los "Parametro"->"Valor"
        foreach($fileredConfigDets as $fileredConfigDet)
        {
            //Obtenemos el nombre de la columna para obtener el valor despues
            $valorColumna = $fileredConfigDet->REFERENCIA;
            $soloColumna = str_replace($tabla.".", '', $valorColumna);
            $first_names = array_column($listaValores, $soloColumna);

            // print_r('Parametro: '.$valorColumna.'-Valor: '.$first_names[0]." -- ");
            $valor = "";
            if(count($first_names) > 0)
               $valor = $first_names[0];
            $paramCollection->push(['Id'=>$fileredConfigDet->ID, 
                                    'Parametro'=>$valorColumna, 
                                    'Valor'=>$valor,
                                    'TipoDato'=>$fileredConfigDet->TIPO_DATO_ID]);
        }

        //Cargamos los valores de Búsqueda para las otras tablas
        if($tabla == 'HR')
        {
            // print_r($tabla);
            $ordenCompraId = $listaValores[0]->OF_FSC;
        }
        elseif($tabla == 'CUSTOMER_ORDER') 
        {
            if(count($listaValores) > 0)
            {
               $clienteId = $listaValores[0]->CUSTOMER_ID;
            }
        }
    }

    //Finalmente le agregamos el elemento con el Numero de Certificado
    //1. Vamos a Buscar el registro con la referencia de NumeroCertificado
    $configDetNumCert = CertificadoConfigDet::where('CERTIFICADO_CONFIG_ID', $config->ID)
                    ->where('REFERENCIA', 'NumeroCertificado')->first();
    //2. Agregamos a mano el Numero de Certificado
    $paramCollection->push(['Id'=>$configDetNumCert->ID, 
    'Parametro'=>'NumeroCertificado', 
    'Valor'=>Carbon::now()->format('Ymd').$piezaNro,
    'TipoDato'=>$configDetNumCert->TIPO_DATO_ID]);

    return $paramCollection;
}

function armarListaParametrosManuales($paramCollection, $piezaNro, $tipoCertificadoId)
{
    //Obtenemos primero el Certificado Config de acuerdo al número de parte
    $config = buscarCertificadoConfig($piezaNro, $tipoCertificadoId);
    //Obtenemos ahora la lista de variables a reemplazar en la plantilla
    $configDets = CertificadoConfigDet::where('CERTIFICADO_CONFIG_ID', $config->ID)
                    ->where('INGRESO_MANUAL', 1)->get();

    foreach($configDets as $configDet)
    {
        //Recopilamos la lista de parámetros a llenar
        $paramCollection->push(['Id'=> $configDet->ID, 
                                'Parametro'=>$configDet->REFERENCIA, 
                                'Valor'=>'',
                                'TipoDato'=>$configDet->TIPO_DATO_ID]);
    }
    
    return $paramCollection;
}

function armarListaValoresCargados($paramCollection, $certificadoId)
{
    // $certificado = Certificado::where('PIEZA', $nroPieza)->first();
    $certificadoDet = CertificadoDet::where('CERTIFICADO_ID', $certificadoId)->get();

    foreach($certificadoDet as $certDet)
    {
        $paramCollection->push(['Id'=> $certDet->CERTIFICADO_CONFIG_DET_ID, 
                                'Parametro'=>$certDet->REFERENCIA, 
                                'Valor'=>$certDet->VALOR,
                                'TipoDato'=>$certDet->CertificadoConfigDet->TIPO_DATO_ID]);
    }

    return $paramCollection;
}

//Devolvemos un set de controles con Etiqueta/Control de carga según el tipo de dato
function procesarCargaDato($certificadoConfigDet, $paramCollection)
{
    $controlTipo = "";
    $name = "";
    $value = "";
    $etiqueta = "";
    $class = "";
    $functionJs = "";
    $checked = "";
    $unidadMedida = "";
    $step = "";
    $customData = "";
    $html = "";

    //Si viene sin tipo de datos asociado, predefinimos 'text'
    $controlTipo = ($certificadoConfigDet->TipoDato != null ? $certificadoConfigDet->TipoDato->TYPE_ID : 'text');
    //Configuramos Step Any para que el control tipo Number nos permita tomar valores en decimales
    if($controlTipo == "number")
    {
       $step = "step='any'";
    }
    $etiqueta = $certificadoConfigDet->ETIQUETA;
    $disabled = ($certificadoConfigDet->EDITABLE == 0 ? 'readonly' : '');
    
    //Si el control es del tipo Radio
    if($certificadoConfigDet->TipoDato != null && $certificadoConfigDet->TipoDato->ID == 4)
    {
        $name = substr($certificadoConfigDet->REFERENCIA, 0, strpos($certificadoConfigDet->REFERENCIA,"."));
        $value = substr($certificadoConfigDet->REFERENCIA, strpos($certificadoConfigDet->REFERENCIA,".") + 1);

        //Si a partir de este Chack, aplicamos conversion de mm-pulgadas, asociamos el Js de Conversion
        if($certificadoConfigDet->DISPARA_CONVERSION == 1)
        {
           $functionJs = "onclick='unidadMedidaCheck()'";
        }
        if($certificadoConfigDet->REFERENCIA == "Dimension.MM")
        {
            $checked = "checked";
        }
    }
    else
    {
        $class = "form-control";

        $name = $certificadoConfigDet->REFERENCIA;
       if($certificadoConfigDet->INGRESO_MANUAL == 1)
       {
          $value = "";
       }
       else
       {
          //Obtenemos el elemento de la lista de valores según la REFERENCIA
          $array = $paramCollection->sole('Parametro', $certificadoConfigDet->REFERENCIA);
          //Obtenemos entonces el valor
          $value = Arr::get($array, 'Valor');
       }
       if($certificadoConfigDet->APLICA_CONVERSION == 1)
       {
           $unidadMedida = "unidadMedida='mm'";
       }
    }

    //Si el tipo de dato es del tipo Imagen
    if($certificadoConfigDet->TipoDato != null && $certificadoConfigDet->TipoDato->ID == 5)
    {
        $html = "<label for='".$certificadoConfigDet->REFERENCIA."'>".$etiqueta."</label>
            <input type='".$controlTipo."' class='".$class."' id='".$certificadoConfigDet->REFERENCIA."' name='".$name."' accept='image/*'
            onchange=\"updatePreview(this, '".$certificadoConfigDet->REFERENCIA."_IMG')\"/>
            <img id='".$certificadoConfigDet->REFERENCIA."_IMG' style='width: 100px; height: 100px'>";
    }
    //Si el control es del tipo TextArea
    else if($certificadoConfigDet->TipoDato != null && $certificadoConfigDet->TipoDato->ID == 6)
    {
        $html = "<label for='".$certificadoConfigDet->REFERENCIA."'>".$etiqueta."</label>
        <textarea  id='".$certificadoConfigDet->REFERENCIA."' name='".$certificadoConfigDet->REFERENCIA."' 
        class='".$class."'></textarea>";
    }
    else
    {
        $html = "<label for='".$certificadoConfigDet->REFERENCIA."'>".$etiqueta."</label>
            <input ".$step." type='".$controlTipo."' class='".$class."' id='".$certificadoConfigDet->REFERENCIA.
            "' name='".$name."' value='".$value."' ".$disabled." ".$checked." ".$unidadMedida." ".$functionJs." ".$customData."/>";
    }

    // if($certificadoConfigDet->TipoDato->ID == 5)
    // {
    //     dd($html);
    // }

    return $html;
}

$unidadMedidaEdicion = "";
function setUnidadMedida($certificadoDet)
{
    //Hacemos referencia a la variable que vamos a modificar para posterior uso
    global $unidadMedidaEdicion;

    $certificadoDetMM = $certificadoDet->reject(function($value){
        return $value->REFERENCIA != 'Dimension.MM';})->first();

    if($certificadoDetMM != null)
    {
        if($certificadoDetMM->VALOR == "true")
        {
            $unidadMedidaEdicion = "unidadMedida='mm'";
        }
        else{
            $unidadMedidaEdicion = "unidadMedida='pulg'";
        }
    }
}

function procesarEdicionDato($certificadoDet)
{
    //Hacemos referencia a la variable y saber que tipo de unidad de medida hemos elegido
    global $unidadMedidaEdicion;

    $controlTipo = "";
    $name = $certificadoDet->CertificadoConfigDet->REFERENCIA;
    $value = $certificadoDet->VALOR;
    $etiqueta = "";
    $class = "";
    $checked = "";
    $functionJs = "";
    $unidadMedida = "";
    $step = "";
    
    $certificadoConfigDet = $certificadoDet->CertificadoConfigDet;

    //Si viene sin tipo de datos asociado, predefinimos 'text'
    $controlTipo = ($certificadoConfigDet->TipoDato != null ? $certificadoConfigDet->TipoDato->TYPE_ID : 'text');
    //Configuramos Step Any para que el control tipo Number nos permita tomar valores en decimales
    if($controlTipo == "number")
    {
       $step = "step='any'";
    }
    $etiqueta = $certificadoConfigDet->ETIQUETA;
    $disabled = ($certificadoConfigDet->EDITABLE == 0 ? 'readonly' : '');

    //Si el control es del tipo Radio
    if($certificadoConfigDet->TipoDato != null && $certificadoConfigDet->TipoDato->ID == 4)
    {
        $name = substr($certificadoConfigDet->REFERENCIA, 0, strpos($certificadoConfigDet->REFERENCIA,"."));
        $value = substr($certificadoConfigDet->REFERENCIA, strpos($certificadoConfigDet->REFERENCIA,".") + 1);
        //Al Option Button lo marcamos como chequeado si viene el dato con la 'X'
        if($certificadoDet->VALOR == "true")
        {
            $checked = "checked";
        }
        if($certificadoConfigDet->DISPARA_CONVERSION == 1)
        {
           $functionJs = "onclick='unidadMedidaCheck()'";
        }
    }
    else
    {
        $class = "form-control";

        if($certificadoConfigDet->APLICA_CONVERSION == 1)
        {
            $unidadMedida = $unidadMedidaEdicion;
        }
    }

    //Si el tipo de dato es del tipo Imagen
    if($certificadoConfigDet->TipoDato != null && $certificadoConfigDet->TipoDato->ID == 5)
    {
        $carpeta = Storage::disk('ensayos')->url('');
        // $archivo = Str::replace("\", "/", $value);
        // $html = "<label for='".$certificadoConfigDet->REFERENCIA."'>".$etiqueta."</label>
        //         <input type='".$controlTipo."' value='".$carpeta.$value."' class='".$class."' id='".$certificadoConfigDet->REFERENCIA."' name='".$name."' accept='image/*'/>";

        $html = "<label for='".$certificadoConfigDet->REFERENCIA."'>".$etiqueta."</label>
        <input type='".$controlTipo."' class='".$class."' id='".$certificadoConfigDet->REFERENCIA."' name='".$name."' accept='image/*'
        onchange=\"updatePreview(this, '".$certificadoConfigDet->REFERENCIA."_IMG')\"/>
        <img id='".$certificadoConfigDet->REFERENCIA."_IMG' style='width: 100px; height: 100px'
        src='".$carpeta.$value."'>";
    }
    //Si el control es del tipo TextArea
    else if($certificadoConfigDet->TipoDato != null && $certificadoConfigDet->TipoDato->ID == 6)
    {
        $html = "<label for='".$certificadoConfigDet->REFERENCIA."'>".$etiqueta."</label>
        <textarea  id='".$certificadoConfigDet->REFERENCIA."' name='".$certificadoConfigDet->REFERENCIA."' 
        class='".$class."'>".$value."</textarea>";
    }
    else
    {
        $html = "<label>".$etiqueta."</label>
        <input ".$step." type='".$controlTipo."' class='".$class."' id='".$certificadoDet->ID.
            "' name='".$name."' value='".$value."' ".$disabled." ".$checked." ".$unidadMedida." ".$functionJs."/>";
    }
    
    return $html;
}

function procesarPlantilla($certificadoId)
{
    $certificado = Certificado::find($certificadoId);

    // dd($certificado);

    //Coleccion de lista de parámetros atomáticos a reemplazar CON sus valores
    $paramCollection = collect([]);
    $paramCollection = armarListaValoresCargados($paramCollection, $certificadoId);

    //Obtenemos la plantilla para despues procesar el HTML
    $modeloHtml = $certificado->CUERPO;

    //Aquí hacemos efectivo el reemplazo de variables por los datos extraidos de la base
    foreach($paramCollection as $param)
    {
        if(Arr::get($param, 'TipoDato') == 5)
        {
            $urlArchivo = Storage::disk('ensayos')->url('').Arr::get($param, 'Valor');
            //dd($urlArchivo);
            $valor = $urlArchivo;
        }
        else
        {
            // dd(Arr::get($param, 'Parametro'));
            // dd(Arr::get($param, 'Valor'));
            $valor = Arr::get($param, 'Valor');
            if($valor == "true"){
            $valor = "<img src='@RutaImagenes@/checkSi.png' width='8' height='8' />";
            }
            else if ($valor == "false"){
                $valor = "<img src='@RutaImagenes@/checkNo.png' width='8' height='8' />";
            }
        }
        $modeloHtml = Str::replace(Arr::get($param, 'Parametro'), $valor, $modeloHtml);
    }

    //Reemplazamos la variable fija de fecha hoy
    $modeloHtml = Str::replace('@FechaHoy', Carbon::now()->format('d-m-Y'), $modeloHtml);
    
    $urlPlano = Storage::disk('planos')->url($certificado->PLANO);

    $urlImagenes = Storage::disk('imagenes')->url('LogoFSC.jpg');
    $urlImagenes = str_replace('/LogoFSC.jpg', '', $urlImagenes);

    //dd($urlImagenes);
    
    //Reemplazamos la ruta del plano a cargar en el certificado
    $modeloHtml = Str::replace('@Plano@', $urlPlano, $modeloHtml);
    //Reemplazamos la ruta de las imagenes a cargar en el certificado
    $modeloHtml = Str::replace('@RutaImagenes@', $urlImagenes, $modeloHtml);

    return $modeloHtml;
}
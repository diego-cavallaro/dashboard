@extends('layouts.dashboard')

@push('styles')
    <link href="/vendor/summernote/summernote-lite.min.css" rel="stylesheet">
    <style>
        input[type="file"] {
            /* border: thin solid rgb(169, 193, 216);
            border-radius: 5px;
            height: 38px; */
        }
        /* file upload button */
        input[type="file"]::file-selector-button {
            display: none;
        }

     </style>
@endpush

@section('content')
    <form action="{{ route('certificados.store')}}" enctype='multipart/form-data' method="POST">
        @csrf
        @method("post")
        <div class="card card-secondary bg-light">
            <input type="hidden" name="nroPieza" value="{{$hr->PIEZA}}">
            <input type="hidden" name="nroParte" value="{{$hr->CODIGO}}">
            <input type="hidden" name="tipoCertificadoId" value={{$tipoCertificadoId}}>
            <div class="card-header">
                <h3 class="card-title">Pieza: {{$hr->PIEZA}} | Cliente: {{$hr->CustomerOrder->Customer->NAME}}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- Recorremos la lista de variables para ir armando los controles de carga --}}
                    @foreach ($configDetsManuales as $certificadoConfigDet)
                        <?php $anchoColumna = "form-group col-md-".$certificadoConfigDet->ANCHO_COLUMNA; ?>
                        <div class="{{$anchoColumna}}" id="{{$certificadoConfigDet->REFERENCIA}}">
                            {!! procesarCargaDato($certificadoConfigDet, null) !!}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="card card-secondary bg-light">
            <div class="card-header">
                <h3 class="card-title">Referencias</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- Recorremos la lista de variables para ir armando los controles de carga --}}
                    @foreach ($configDetsAutomaticos as $certificadoConfigDet)
                        <?php $anchoColumna = "form-group col-md-".$certificadoConfigDet->ANCHO_COLUMNA; ?>
                        <div class="{{$anchoColumna}}" id="{{$certificadoConfigDet->REFERENCIA}}">
                            {!! procesarCargaDato($certificadoConfigDet, $paramCollection) !!}
                        </div>
                    @endforeach
                </div>
                <div class="row">
                    <label for="VB">Vb</label>&nbsp;<br>
                    <input type="checkbox" name="VB" id="VB">
                </div>
            </div>
        </div>
        <button class="btn btn-primary" onclick="deshabilitaGraba(this);">
            {{-- <i class="fa-solid fa-floppy-disk"></i> --}}
            Grabar
        </button>
        <a href="{{ route('certificados.show', $tipoCertificadoId) }}" class="btn btn-secondary">
          {{-- <i class="fa-solid fa-ban"></i> --}}
          Cancelar
        </a>        
    </form>
@stop

@push('scripts')
    <script src="/vendor/jquery/jquery.min.js"></script>
    <script src="/vendor/summernote/summernote-lite.min.js"></script>
    <script src="/vendor/summernote/lang/summernote-es-ES.js"></script>

    <script>

      function deshabilitaGraba(sender)
      {
        sender.disabled= true;
        sender.innerHTML = "<span class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></span> Grabando...";
        sender.form.submit();
      }

      function unidadMedidaCheck()
      {
        //  llenarSelectDetalle(this);
        const collection = document.getElementsByTagName('input');
        for (let i = 0; i < collection.length; i++)
        {
            if(collection[i].hasAttribute('unidadMedida'))
            {
               //alert(collection[i].name + ' ' + collection[i].getAttribute('unidadMedida'));
               let valor = collection[i].value;
               //Si es 'mm' pasamos a pulgadas
               if(collection[i].getAttribute('unidadMedida') == 'mm')
               {
                valor = (valor / 25.4).toFixed(4);
                collection[i].setAttribute('unidadMedida', 'pulg');
               }
               else
               {
                valor = (valor * 25.4).toFixed(3);
                collection[i].setAttribute('unidadMedida', 'mm');
               }
               collection[i].value = valor;
            }
        }
      }

      function updatePreview(input, target) {
        let file = input.files[0];
        let reader = new FileReader();

        reader.readAsDataURL(file);
        reader.onload = function () {
           let img = document.getElementById(target);
           // can also use "this.result"
           img.src = reader.result;
        }
      }
    </script>
@endpush
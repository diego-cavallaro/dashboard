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
    <form action="{{ route('certificados.update')}}" enctype='multipart/form-data' method="POST">
        @csrf
        @method("post")
        
        {!! setUnidadMedida($certificadoDet) !!}
        
        <div class="card card-secondary bg-light">
            <input type="hidden" name="certificadoId" value="{{$certificado->ID}}">
            <input type="hidden" name="tipoCertificadoId" value="{{$tipoCertificadoId}}">
            <div class="card-header">
                <h3 class="card-title">Certificado: {{$certificado->ID}} | Pieza: {{$certificado->PIEZA}} | Cliente: {{$certificado->Hr->CustomerOrder->Customer->NAME}}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- Recorremos los datos manuales de la base para ir armando los controles de carga --}}
                    @foreach ($certificadoDet as $certDet)
                        {{-- @php
                           $certificadoConfigDet = $certDet->CertificadoConfigDet;
                        @endphp --}}
                        @if($certDet->CertificadoConfigDet->INGRESO_MANUAL == 1)
                            <?php $anchoColumna = "form-group col-md-".$certDet->CertificadoConfigDet->ANCHO_COLUMNA; ?>
                            <div class="{{$anchoColumna}}" id="{{$certDet->REFERENCIA}}">
                                {!! procesarEdicionDato($certDet) !!}
                            </div>
                        @endif
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
                    {{-- Recorremos los datos automaticos de la base para ir armando los controles de carga --}}
                    @foreach ($certificadoDet as $certDet)
                        @if($certDet->CertificadoConfigDet->INGRESO_MANUAL == 0)
                            <?php $anchoColumna = "form-group col-md-".$certDet->CertificadoConfigDet->ANCHO_COLUMNA; ?>
                            <div class="{{$anchoColumna}}" id="{{$certDet->REFERENCIA}}">
                                {!! procesarEdicionDato($certDet) !!}
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="row">
                    <label>Vb</label>&nbsp;<br>
                    <input type="checkbox" name="VB" id="VB" {{$certificado->VB == 1 ? 'checked="checked"' : ''}}>
                </div>
            </div>
        </div>

        <button id="btnGrabar" class="btn btn-primary" onclick="deshabilitaGraba(this);">
            {{-- <i class="fa-solid fa-floppy-disk"></i> --}}
            Grabar
        </button>
        <a href="{{ route('certificados.show', $tipoCertificadoId, 1) }}" class="btn btn-secondary">
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
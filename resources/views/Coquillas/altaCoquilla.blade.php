@extends('layouts.dashboard')

@push('styles')
    <link href="/vendor/summernote/summernote-lite.min.css" rel="stylesheet">
    
    <style>

    </style>
@endpush

@section('content')
    <form action="{{ route('coquillas.store')}}" enctype='multipart/form-data' method="POST">
        @csrf
        @method("POST")
                
        <div class="card card-secondary bg-light">
            {{-- <input type="hidden" name="resourceId" value="{{$coquilla->RESOURCE_ID}}"> --}}

            <div class="card-header">
                <h3 class="card-title">Nueva Coquilla</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-2">
                        <label for="Coquilla">Coquilla</label>
                        <input type='text' class='form-control' id='Coquilla' name='Coquilla' 
                            onkeyup='goMayuscula(this);' value="{{old('Coquilla')}}" />
                     </div>
                    <div class="form-group col-md-2">
                        <label for="EstadoCoquilla">Estado</label>
                        <select name="EstadoCoquilla" id="EstadoCoquilla" class="form-control">
                           <option value = {{0}} {{(old('EstadoCoquilla') == 0 ? 'selected' : '')}}>Seleccione...</option>
                           @foreach ($estadosCoquilla as $estado)
                              <option value="{{ $estado->ID }}" {{(old('EstadoCoquilla') == $estado->ID ? 'selected' : '')}} >{{ $estado->DESCRIPCION }}</option>
                           @endforeach
                        </select>
                     </div>
                     <div class="form-group col-md-2">
                        <label for="Diametro1">Diametro 1</label>
                        <input type='number' class='form-control' id='Diametro1' name='Diametro1' value="{{old('Diametro1')}}"/>
                     </div>
                     <div class="form-group col-md-2">
                        <label for="Diametro2">Diametro 2</label>
                        <input type='number' class='form-control' id='Diametro2' name='Diametro2' value="{{old('Diametro2')}}"/>
                     </div>
                     <div class="form-group col-md-2">
                        <label for="Altura">Altura</label>
                        <input type='number' class='form-control' id='Altura' name='Altura' value="{{old('Altura')}}">
                     </div>
                     <div class="form-group col-md-2">
                        <label for="Fecha">Fecha Disponibilidad</label>
                        <input type='date' class='form-control' id='Fecha' name='Fecha' value="{{old('Fecha')}}">
                     </div>
                </div>
                <div class="row col-md-12">
                    @error('Coquilla')
                        <span style="color: red">{{ $message }}</span>
                    @enderror
                </div>
                <div class="row col-md-12">
                    @error('Fecha')
                      <span style="color: red">{{ $message }}</span>
                    @enderror
                </div>
                <div class="row col-md-12">
                    @error('EstadoCoquilla')
                      <span style="color: red">{{ $message }}</span>
                    @enderror
                </div>
                <div class="row">
                    <div class="form-group col-md-2">
                        <label for="ConAgujero">Con Agujero</label>
                        <input type="checkbox" name="ConAgujero" id="ConAgujero" 
                               onclick="checkConAgujero()" {{old('ConAgujero') !== null ? 'checked' : ''}}>
                     </div>
                     <div class="form-group col-md-2">
                        <label for="ConCanal">Con Canal</label>
                        <input type="checkbox" name="ConCanal" id="ConCanal" 
                               onclick="checkConCanal()" {{old('ConCanal') !== null ? 'checked' : ''}}>
                     </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-2">
                        <label for="Pista1">Pista 1</label>
                        <input type='number' class='form-control' id='Pista1' name='Pista1' value="{{old('Pista1')}}"/>
                     </div>
                     <div class="form-group col-md-2">
                        <label for="Pista2">Pista 2</label>
                        <input type='number' class='form-control' id='Pista2' name='Pista2' value="{{old('Pista2')}}"/>
                     </div>
                     <div class="form-group col-md-2">
                        <label for="FechaEdicionPista">Fecha Pistas</label>
                        <input type='date' class='form-control' id='FechaEdicionPista' value={{Carbon\Carbon::now()}} name='FechaEdicionPista' disabled>
                     </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-10">
                        <label for="Observaciones">Observaciones</label>
                        <textarea class='form-control' id='Observaciones' name='Observaciones'>{{old('Observaciones')}}</textarea>
                     </div>                     
                </div>
            </div>
        </div>

        <button type="submit" id="btnGrabar" class="btn btn-primary" onclick="deshabilitaGraba(this);">
            {{-- <i class="fa-solid fa-floppy-disk"></i> --}}
            Grabar
        </button>
        <a href="{{ route('coquillas.show') }}" class="btn btn-secondary">
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

        function goMayuscula(e) {
           e.value = e.value.toUpperCase();
        }

        function checkConAgujero()
        {
            $("#ConCanal").prop("checked", false);
        }

        function checkConCanal()
        {
            $("#ConAgujero").prop("checked", false);
        }

    </script>
@endpush
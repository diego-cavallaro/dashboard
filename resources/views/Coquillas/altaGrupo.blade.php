@extends('layouts.dashboard')

@push('styles')
    <link href="/vendor/summernote/summernote-lite.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    
    <style>

    </style>
@endpush

@section('content')
<form action="{{ route('grupos.store')}}" enctype='multipart/form-data' method="POST">
    @csrf
    @method("POST")
            
    <div class="card card-secondary bg-light">
        {{-- <input type="hidden" name="resourceId" value="{{$coquilla->RESOURCE_ID}}"> --}}

        <div class="card-header">
            <h3 class="card-title">Nuevo Grupo</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-2">
                    <label for="Grupo">Grupo</label>
                    <input type='text' class='form-control' id='Grupo' name='Grupo' 
                        onkeyup='goMayuscula(this);' value="{{old('Grupo')}}" />
                    @error('Grupo')
                        <span style="color: red">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="Descripcion">Descripción</label>
                    <input type='text' class='form-control' id='Descripcion' name='Descripcion' value="{{old('Descripcion')}}"/>
                    @error('Descripcion')
                        <span style="color: red">{{ $message }}</span>
                    @enderror
                 </div>
            </div>
        </div>
    </div>

    <button type="submit" id="btnGrabar" class="btn btn-primary" onclick="deshabilitaGraba(this);">
        Grabar
    </button>
    <a href="{{ route('grupos.show') }}" class="btn btn-secondary">
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

    </script>
@endpush
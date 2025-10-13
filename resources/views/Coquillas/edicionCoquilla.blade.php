@extends('layouts.dashboard')

@push('styles')
    <link href="/vendor/summernote/summernote-lite.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    
    <style>

    </style>
@endpush

@section('content')
    <form action="{{ route('coquillas.update')}}" enctype='multipart/form-data' method="POST">
        @csrf
                
        <div class="card card-secondary bg-light">
            <input type="hidden" name="resourceId" value="{{$coquilla->RESOURCE_ID}}">
            <input type="hidden" name="Coquilla" value="{{$coquilla->RESOURCE_ID}}">

            <div class="card-header">
                <h3 class="card-title">Coquilla: {{$coquilla->RESOURCE_ID}}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="form-group col-md-5">
                                <label for="EstadoCoquilla">Estado</label>
                                <select name="EstadoCoquilla" id="EstadoCoquilla" class="form-control">
                                    <option value = {{0}} {{ old('EstadoCoquilla', $coquilla->ESTADO_COQUILLA_ID) == null ? "selected" : "" }}>Seleccione Estado</option>
                                    @foreach ($estadosCoquilla as $estado)
                                        <option value="{{ $estado->ID }}" {{$estado->ID == old('EstadoCoquilla', $coquilla->ESTADO_COQUILLA_ID) ? "selected" : ""}} >{{ $estado->DESCRIPCION }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                {{-- {{dd($coquilla->FECHA);}} --}}
                                <label for="Fecha">Fecha Disponibilidad</label>
                                <input type='date' class='form-control' id='Fecha' name='Fecha' value="{{old('Fecha', Carbon\Carbon::parse($coquilla->FECHA)->format("Y-m-d"))}}">
                            </div>
                        </div>
                        <div class="row col-md-12">
                            @error('Fecha')
                               <span style="color: red">{{ $message }}</span>
                            @enderror
                            <div class="row col-md-12">
                                @error('EstadoCoquilla')
                                  <span style="color: red">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="Diametro1">Diametro 1</label>
                                <input type='number' class='form-control' id='Diametro1' name='Diametro1' value='{{old('Diametro1', $coquilla->DIAMETRO1)}}'/>
                             </div>
                             <div class="form-group col-md-4">
                                <label for="Diametro2">Diametro 2</label>
                                <input type='number' class='form-control' id='Diametro2' name='Diametro2' value='{{old('Diametro2', $coquilla->DIAMETRO2)}}'/>
                             </div>
                             <div class="form-group col-md-4">
                                <label for="Altura">Altura</label>
                                <input type='number' class='form-control' id='Altura' name='Altura' value='{{old('Altura', $coquilla->ALTURA)}}'>
                             </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="ConAgujero">Con Agujero</label>
                                <input type="checkbox" name="ConAgujero" id="ConAgujero" 
                                       {{old('ConAgujero') ? 'checked' : (old('ConCanal') === null & $coquilla->CON_AGUJERO == 1 ? 'checked' : '')}} 
                                       onclick="checkConAgujero()">
                                       
                             </div>
                             <div class="form-group col-md-4">
                                <label for="ConCanal">Con Canal</label>
                                <input type="checkbox" name="ConCanal" id="ConCanal" 
                                       {{old('ConCanal') ? 'checked' :(old('ConAgujero') === null & $coquilla->CON_CANAL == 1 ? 'checked' : '')}} 
                                       onclick="checkConCanal()">
                             </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="Pista1">Pista 1</label>
                                <input type='number' class='form-control' id='Pista1' name='Pista1' value='{{old('Pista1', $coquilla->PISTA1)}}'/>
                             </div>
                             <div class="form-group col-md-4">
                                <label for="Pista2">Pista 2</label>
                                <input type='number' class='form-control' id='Pista2' name='Pista2' value='{{old('Pista2', $coquilla->PISTA2)}}'/>
                             </div>
                             <div class="form-group col-md-4">
                                <label for="FechaEdicionPista">Fecha Pistas</label>
                                <input type='date' class='form-control' id='FechaEdicionPista' name='FechaEdicionPista' disabled value={{$coquilla->FECHA_EDICION_PISTA == null ? "" : Carbon\Carbon::parse($coquilla->FECHA_EDICION_PISTA)->format("Y-m-d")}}>
                             </div>
                        </div>
                    </div>
                    {{-- GRUPOS de la Coquilla --}}
                    <div class="col-md-6 text-right">
                        <!-- Boton trigger del Formulario Modal -->
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#staticBackdrop">
                            Asignar Grupo
                        </button>
                        <table id='grupos'class="table table-bordered table-striped table-sm" 
                            style="display: block; height: 250px; overflow-y: auto;">
                            <thead>
                                <th style="width: 30vw">Grupo</th>
                                <th style="width: 60vw">Descripcion</th>
                                <th></th>
                            </thead>
                            <tbody style="overflow-y: scroll;">
                                @foreach ($shopGroups as $shopGroup)
                                <tr>
                                    <td>{{$shopGroup->GROUP_RESOURCE_ID}}</td>
                                    <td>{{$shopGroup->GroupResource->DESCRIPTION}}</td>
                                    <td class="td-actions text-center">
                                        {{-- <form action="{{ route('coquillas.destroyGrupo', [$coquilla->RESOURCE_ID, $shopGroup->GROUP_RESOURCE_ID]) }}" 
                                              method="POST" style="display: inline-block;">
                                            @csrf
                                            <button class="btn btn-primary btn-sm">
                                                
                                            </button>
                                        </form> --}}
                                        <a href="{{URL::to('/Coquillas/Grupo/Delete/'.$coquilla->RESOURCE_ID."/".$shopGroup->GROUP_RESOURCE_ID)}}" >
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <label for="Observaciones">Observaciones</label>
                    <textarea class='form-control' id='Observaciones' name='Observaciones'>{{old('Observaciones', $coquilla->OBSERVACIONES)}}</textarea>
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

    <!--#### Formulario Modal de Selección y Filtrado de Grupo ####-->
    <div class="modal fade" id="staticBackdrop" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Seleccionar Grupo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="height: 60vh; overflow-y: auto;">
                    <table id='listaGrupos' class="table table-bordered table-striped table-sm" data-search="true"
                        data-custom-search="customSearch">
                        <thead>
                        <th style="width: 25vw">Grupo</th>
                        <th style="width: 50vw">Descripcion</th>
                        <th style="width: 25vw"><input type="text" class='form-control' id="filtro" onkeyup="funcionFiltro()" placeholder="Búsqueda"></th>
                        </thead>
                        <tbody>
                        @foreach ($gruposCoquilla as $grupoCoquilla)
                            <tr>
                                <td>{{$grupoCoquilla->ID}}</td>
                                <td>{{$grupoCoquilla->DESCRIPTION}}</td>
                                <td class="text-center">
                                    <form action="{{ route('coquillas.storeGrupo', [$coquilla->RESOURCE_ID, $grupoCoquilla->ID]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            Seleccionar
                                    </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>                
                </div>
                <div class="modal-footer">
                    {{-- <button type="button" class="btn btn-primary">Grabar</button> --}}
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
  
@stop

@push('scripts')
    <script src="/vendor/jquery/jquery.min.js"></script>
    <script src="/vendor/summernote/summernote-lite.min.js"></script>
    <script src="/vendor/summernote/lang/summernote-es-ES.js"></script>

    <script>

        function funcionFiltro() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("filtro");
            filter = input.value.toUpperCase();
            table = document.getElementById("listaGrupos");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++)
            {
                td = tr[i].getElementsByTagName("td")[0];
                if (td) 
                {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }

        function deshabilitaGraba(sender)
        {
            sender.disabled= true;
            sender.innerHTML = "<span class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></span> Grabando...";
            sender.form.submit();
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
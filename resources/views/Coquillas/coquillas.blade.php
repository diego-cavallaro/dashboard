@extends('layouts.dashboard')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.bootstrap4.min.css">
@endpush

@section('content')
 <div class="card-body">
   <form action="{{route('coquillas.filter')}}" method="POST">
      @csrf
      {{-- <div class="card-body"> --}}
         <div class="row">
            <div class="form-group col-md-2">
               <label for="EstadoCoquilla">Estado</label>
               <select name="EstadoCoquilla" id="EstadoCoquilla" class="form-control">
                  <option value = {{0}} {{$estadoCoquilla == 0 ? "selected" : "" }}>Seleccione...</option>
                  @foreach ($estadosCoquilla as $estado)
                     <option value="{{ $estado->ID }}" {{$estado->ID == $estadoCoquilla ? "selected" : ""}} >{{ $estado->DESCRIPCION }}</option>
                  @endforeach
               </select>
            </div>
            {{--------------------------------------------------------------------------------------------}}
            <div class="form-group col-md-2">
               <label for="ConAgujero">Con Agujero</label>
               <input type="checkbox" name="ConAgujero" id="ConAgujero" {{$conAgujero == 1 ? 'checked' : ''}} onclick="checkConAgujero()">
            </div>
            <div class="form-group col-md-2">
               <label for="ConCanal">Con Canal</label>
               <input type="checkbox" name="ConCanal" id="ConCanal" {{$conCanal == 1 ? 'checked' : ''}} onclick="checkConCanal()">
            </div>
            <div class="form-group col-md-6 text-right">
               <a href="{{ route('coquillas.create') }}" class="btn btn-primary">
                  Nueva Coquilla
               </a>
            </div>
            {{--------------------------------------------------------------------------------------------}}
         </div>
      {{-- </div> --}}
      {{-- <input type="hidden" name="tipoCertificadoId" value={{$tipoCertificadoId}}>
      <input type="hidden" name="cobVb" value="{{$conVb}}"> --}}
      <button id="btnRefrescar" class="d-none">Refrescar</button>
   </form>
   <table id='doc'class="table table-bordered table-hover">
       <thead>
          <th>Coquilla</th>
          <th>Diametro 1</th>
          <th>Diametro 2</th>
          <th>Altura</th>
          <th>Estado</th>
          <th>Fecha Disponibilidad</th>
          <th>Canal</th>
          <th>Agujero</th>
          <th></th>
       </thead>
       <tbody>
          @foreach ($coquillas as $coquilla)
             <tr>
                <td>{{$coquilla->RESOURCE_ID}}</td>
                <td class="text-right">{{number_format($coquilla->DIAMETRO1, 2)}}</td>
                <td class="text-right">{{number_format($coquilla->DIAMETRO2, 2)}}</td>
                <td class="text-right">{{number_format($coquilla->ALTURA, 2)}}</td>
                <td class="text-center">
                    @if($coquilla->EstadoCoquilla != null)    
                       {{$coquilla->EstadoCoquilla->DESCRIPCION}}
                    @else
                       {{"S/D"}}
                    @endif
                </td>
                <td class="text-center">{{Carbon\Carbon::createFromDate($coquilla->FECHA)->format('d-m-Y')}}</td>
                <td class="text-center">
                    @if($coquilla->CON_CANAL == 0)    
                       {{"No"}}
                    @else
                       {{"Si"}}
                    @endif
                </td>
                <td class="text-center">
                    @if($coquilla->CON_AGUJERO == 0)    
                       {{"No"}}
                    @else
                       {{"Si"}}
                    @endif
                </td>
                <td class="text-center">
                     {{-- <form action="{{route('coquillas.edit', $coquilla->RESOURCE_ID)}}" method="GET" style="display: inline-block;">
                        @csrf
                        <button class="btn btn-primary btn-sm">
                           Editar
                        </button>
                     </form> --}}
                     <a href="{{ route('coquillas.edit', $coquilla->RESOURCE_ID) }}" class="btn btn-primary">
                        Editar
                     </a>
                </td>
            </tr>
          @endforeach
       </tbody>
    </table>
    <hr>
 </div>
@stop

@section('js')
 <script src=https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js></script>
 <script src=https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js></script>
 <script src=https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js></script>
 <script src=https://cdn.datatables.net/responsive/2.4.0/js/responsive.bootstrap4.min.js></script>
 <script>
   $(document).ready(function () {
    $('#doc').DataTable({
      responsive: true,
      autowith: false,
      "language": {
            "lengthMenu": "Muestra _MENU_ registros por página",
            "zeroRecords": "Nada encontrado",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "No se encontraron registros",
            "infoFiltered": "(filtered from _MAX_ total records)",
            "search": "Búsqueda : ",
            "paginate":{
              "next":"Siguiente",
              "previous":"Anterior",
            }
        }
    });
   });

   // Para capturar el evento del CheckBox y hacer el POST
   $(document).ready(function() {
    $('#ConAgujero:checkbox').bind('change', function(e) {
       $("#btnRefrescar").click(); 
    })
    $('#ConCanal:checkbox').bind('change', function(e) {
       $("#btnRefrescar").click(); 
    })
    $('#EstadoCoquilla').bind('change', function(e) {
       $("#btnRefrescar").click(); 
    })
   });

   function checkConAgujero()
   {
      $("#ConCanal").prop("checked", false);
   }

   function checkConCanal()
   {
      $("#ConAgujero").prop("checked", false);
   }

 </script>
@endsection
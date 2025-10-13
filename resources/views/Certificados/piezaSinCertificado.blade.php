@extends('layouts.dashboard')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.bootstrap4.min.css">
@endpush

@section('content')
<div class="card-body">
   {{-- <select name="filtroPartes" id="filtroPartes" class="form-control">
      <option value="" selected disabled hidden>Seleccione Parte</option>
      @foreach ($partList as $part)
         <option value="{{ $part->PART_ID }}">{{ $part->PART_ID }}</option>
      @endforeach
   </select> --}}
   <form action="{{route('certificados.filter', $tipoCertificadoId)}}" method="POST">
      @csrf
      <label for="VB">Con Vb</label>
      <input type="checkbox" name="VB" id="VB" {{$conVb == 1 ? 'checked' : ''}}>
      <button id="btnRefrescar" class="d-none">Refrescar</button>

      <input type="hidden" name="tipoCertificadoId" value={{$tipoCertificadoId}}>
      <input type="hidden" name="cobVb" value="{{$conVb}}">
   </form>
   <table id='doc'class="table table-bordered table-hover">
       <thead>
          <th>Pieza</th>
          <th>Parte Nro</th>
          <th>OC Cliente</th>
          <th>OC FSC</th>
          <th>Fecha</th>
          <th></th>
       </thead>
       <tbody>
          @foreach ($hrList as $hr)
             <tr>
                <td>{{$hr->PIEZA}}</td>
                <td>{{$hr->CODIGO}}</td>
                <td>{{$hr->OC_CLIENTE}}</td>
                <td>{{$hr->OF_FSC}}</td>
                <td>{{Carbon\Carbon::createFromDate($hr->FECHA)->format('d-m-Y')}}</td>
                <td class="text-center">
                 @if($hr->CERTIFICADO_ID != null)
                     <form action="{{route('certificados.edit', $hr->CERTIFICADO_ID)}}" method="GET" style="display: inline-block;">
                        @csrf
                        <button class="btn btn-primary btn-sm">
                           {{-- <i class="fa-solid fa-pen"></i> --}}
                           Editar
                        </button>
                     </form>
                     <form action="{{route('certificados.vistaPreliminar', $hr->CERTIFICADO_ID)}}" method="GET" style="display: inline-block;">
                        @csrf
                        <button class="btn btn-primary btn-sm">
                           {{-- <i class="fa-solid fa-pen"></i> --}}
                           Ver
                        </button>
                     </form>
                    @else
                     <form action="{{route('certificados.create', [$hr->PIEZA, $tipoCertificadoId])}}" method="GET">
                        @csrf
                        <button class="btn btn-primary btn-sm">
                           {{-- <i class="fa-solid fa-pen"></i> --}}
                           Nuevo
                        </button>
                     </form>
                    @endif
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
    $('#VB:checkbox').bind('change', function(e) {
      // if ($(this).is(':checked')) {
      //    alert('Checked');
      // }
      // else {
      //   alert('Unchecked');
      // }
      $("#btnRefrescar").click(); 
    })
   });
 </script>
@endsection
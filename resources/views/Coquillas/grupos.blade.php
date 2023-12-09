@extends('layouts.dashboard')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.bootstrap4.min.css">
@endpush

@section('content')
 <div class="card-body">
    <div class="form-group col-md-12 text-right">
        <a href="{{ route('grupos.create') }}" class="btn btn-primary">
           Nuevo Grupo
        </a>
     </div>
    <table id='doc'class="table table-bordered table-hover">
        <thead>
           <th>Id</th>
           <th>Descripcion</th>
           <th>Programa</th>
           <th>Prog.Normal</th>
           <th>Auto Report</th>
           <th>Tipo</th>
           {{-- <th>Shift 1</th>
           <th>Shift 2</th>
           <th>Shift 3</th> --}}
           <th></th>
        </thead>
        <tbody>
           @foreach ($gruposCoquilla as $grupoCoquilla)
              <tr>
                 <td>{{$grupoCoquilla->ID}}</td>
                 <td>{{$grupoCoquilla->DESCRIPTION}}</td>
                 <td class="text-center">{{$grupoCoquilla->EXCLUSIVITY == "X" ? "Uno" : "Todo"}}</td>
                 <td class="text-center">{{$grupoCoquilla->SCHEDULE_NORMALLY == "Y" ? "Si" : "No"}}</td>
                 <td class="text-center">{{$grupoCoquilla->AUTO_REPORTING == "Y" ? "Si" : "No"}}</td>
                 <td class="text-center">{{$grupoCoquilla->TYPE}}</td>
                 {{-- <td class="text-center">{{$grupoCoquilla->SHIFT_1_CAPACITY}}</td>
                 <td class="text-center">{{$grupoCoquilla->SHIFT_2_CAPACITY}}</td>
                 <td class="text-center">{{$grupoCoquilla->SHIFT_3_CAPACITY}}</td> --}}
                 <td class="text-center">
                    <a href="{{ route('grupos.edit', $grupoCoquilla->ID) }}" class="btn btn-primary">
                        Editar
                    </a>
                 </td>
             </tr>
           @endforeach
        </tbody>
     </table>
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


 </script>
@endsection
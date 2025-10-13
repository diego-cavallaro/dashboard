@extends('layouts.dashboard')

@section('css')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.bootstrap4.min.css">
@endsection

@section('content')
   <div class="card-body">
      <table id='doc'class="table table-bordered table-hover">
         <thead>
            <th>Id</th>
            <th>Part</th>
            <th>Plantilla</th>
            <th>Referencia</th>
         </thead>
         <tbody>
            @foreach ($configDets as $configDet)
               <tr>
                  <td>{{$configDet->ID}}</td>
                  <td>{{$configDet->CertificadoConfig->PART_ID}}</td>
                  <td>{{$configDet->CertificadoConfig->Plantilla->DESCRIPCION}}</td>
                  <td>{{$configDet->REFERENCIA}}</td>
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
 </script>
@endsection
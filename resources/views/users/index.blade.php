@extends('layouts.dashboard')

    @section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.bootstrap4.min.css">
    @endsection

    @section('content')

        <br>
            <div class="col-12" >
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Lista Usuarios</h3>
                    </div>

                    <div class="card-body">

                        <table id='user'class="table table-sm table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                        <div class="row row-sm">
                            <th>Nombre</th>
                            <th style='text-align:center'>Alias</th>
                            <th style='text-align:center'>Email</th>
                            <th style='text-align:center'>Alta</th>
                            <th style='text-align:center'>Modificado</th>
                            <th style='text-align:center'>Acción</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{$user->name}}</td>
                            <td style='text-align:center'>{{$user->nickName}}</td>
                            <td style='text-align:center'>{{$user->email}}</td>
                            <td style='text-align:center'>{{ Carbon\Carbon::parse($user->created_at)->format('d-m-Y') }}</td>
                            <td style='text-align:center'>{{ Carbon\Carbon::parse($user->update_at)->format('d-m-Y') }}</td>
                            <td style='text-align:center'>
                                <a href ="show/{{$user->id}}" class="btn btn-xs btn-success"> Editar </a>
                                <form method="POST" action="{{ route('users.disable', $user)}}" style="display: inline">
                                    {{csrf_field()}} {{method_field('DELETE')}}
                                    <button class="btn btn-xs btn-danger"> Deshabilitar </button>
                                </form>
                            </td>
                        </tr>
                            
                        @endforeach
                        </tbody>
</div>
                        </table>
                    </div>
                </div>
            </div>
    @stop

@section('js')
    <script src=https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js></script>
    <script src=https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js></script>
    <script src=https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js></script>
    <script src=https://cdn.datatables.net/responsive/2.4.0/js/responsive.bootstrap4.min.js></script>
    <script>
        $(document).ready(function () 
            {
            $('#user').DataTable
                (
                    {
                        responsive: true,
                        autowith: false,
                        "language": {
                                        "lengthMenu": "Muestra _MENU_ registros por página",
                                        "zeroRecords": "Sin documentos encontrados",
                                        "info": "Mostrando página _PAGE_ de _PAGES_",
                                        "infoEmpty": "No se encontraron registros",
                                        "infoFiltered": "(filtered from _MAX_ total records)",
                                        "search": "Búsqueda : ",
                                        "paginate":{
                                                    "next":"Siguiente",
                                                    "previous":"Anterior",
                                                    }
                                    }
                    }
                );
            }
        );
    </script>

  </div>
</div>
@endsection


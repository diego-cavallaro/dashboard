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
                        <h3 class="card-title">Lista de Documentos</h3>
                    </div>

                    <div class="card-body">

                        <div class="float-right">
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#newDocument"> Nuevo Documento </button>
                            <br>                            
                        </div>
                        

                        <table id='doc'class="table table-sm table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th>Título</th>
                            <th style='text-align:center'>Área</th>
                            <th style='text-align:center'>Publicado</th>
                            <th style='text-align:center'>Modificado</th>
                            <th style='text-align:center'>Ambito</th>
                            <th style='text-align:center'>Autor</th>
                            <th style='text-align:center'>Acción</th>
                        </tr>
                        </thead>

                        <tbody>
                            @foreach($docs as $doc)
                                <tr>
                                    <td>{{$doc->title}}</td>
                                    <td style='text-align:center'>{{$doc->area->name}}</td>
                                    <td style='text-align:center'>{{ Carbon\Carbon::parse($doc->published_at)->format('m-Y') }}</td>
                                    <td style='text-align:center'>{{ Carbon\Carbon::parse($doc->update_at)->format('m-Y') }}</td>
                                    <td style='text-align:center'>{{$doc->public}}</td>
                                    <td style='text-align:center'>{{$doc->user->name}}</td>
                                    <td style='text-align:center'> 
                                        <a href ="edit/{{$doc->url}}" class="btn btn-xs btn-info"> Editar </a>
                                        <form method="POST" action="{{ route('docs.destroy', $doc)}}" style="display: inline">
                                            {{csrf_field()}} {{method_field('DELETE')}}
                                            <button class="btn btn-xs btn-danger"> Eliminar </button>
                                        </form>
                                    </td>
                                </tr>                            
                            @endforeach
                        </tbody>

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
            $('#doc').DataTable
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

    <div class="modal fade" id="newDocument" tabindex="-1" role="dialog" aria-labelledby="newDocumentLabel" aria-hidden="true">
        <form method="POST" action="{{route('docs.store')}}">
            {{ csrf_field() }}
            @method('PUT')
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newDocumentLabel">Título del Documento</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <div class="modal-body">
                    <div class="form-group" {{ $errors->has('title')? 'has-error' : ''}}>
                        <input  name="title" 
                            type="text"
                            value="{{old('title')}}" 
                            class="form-control" 
                            placeholder="Minimo 10, máx 80 caracteres, único" 
                            minlength="10" 
                            maxlength="80"
                            required >
                    </div>
                </div>
            <div class="modal-footer">
                <button class="btn btn-primary btn-sm">Crear Documento</button>
            </div>
        </form>
    </div>
  </div>
</div>
@endsection


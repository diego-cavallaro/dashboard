@extends('layouts.dashboard')

    @section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.bootstrap4.min.css">
    @endsection

    @section('content')
    <!-- Main content -->
    <br>
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  <img class="profile-user-img img-fluid img-circle"
                       src="{{ $user->profile_photo_url }}"
                       alt="User profile picture">
                </div>

                <h3 class="profile-username text-center">{{ $user->nickName }}</h3>

                <p class="text-muted text-center"></p>

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Alias</b> <a class="float-right">{{ $user->name }}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Roles</b> <a class="float-right">{{ $user->getRoleNames()->implode(', ') }}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Registrado</b> <a class="float-right">{{ $user->created_at->format('d-m-Y') }}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Modificado</b> <a class="float-right">{{ $user->updated_at->format('d-m-Y') }}</a>
                  </li>
                </ul>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#roles" data-toggle="tab">Roles</a></li>
                  <li class="nav-item"><a class="nav-link" href="#permisos" data-toggle="tab">Permisos</a></li>
                  <li class="nav-item"><a class="nav-link" href="#edit" data-toggle="tab">Editar</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="active tab-pane" id="roles">

                    <!-- Post -->
                  @foreach ($user->roles as $role)

                     <strong>{{$role->name}}</strong>

                     @if ($role->permissions->count())
                     <br>
                     <small>Permisos : {{$role->permissions->pluck('name')->implode(', ')}}</small>
                     @endif
                     

                  @endforeach
                    <!-- /.post -->
                  </div>

                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="permisos">
                    <!-- The timeline -->
                    @forelse ($user->permissions as $permission)

                        <strong>{{$permission->name}}</strong>
                        @empty 
                        <small>Sin permisos adicionales</small>
                    @endforelse
                  </div>
                  <!-- /.tab-pane -->

                  <div class="tab-pane" id="edit">
                    <form class="form-horizontal">
                      <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Nombre</label>
                        <div class="col-sm-10">
                          <input type="email" class="form-control" id="inputName" placeholder="{{ $user->name }}">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                          <input type="email" class="form-control" id="inputEmail" placeholder="{{ $user->email }}">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputName2" class="col-sm-2 col-form-label">Alias</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="inputName2" placeholder="{{ $user->nickName }}">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputExperience" class="col-sm-2 col-form-label">Roles</label>
                        <div class="col-sm-10">
                          <textarea class="form-control" id="inputExperience" placeholder="{{ $user->getRoleNames()->implode(', ') }}"></textarea>
                        </div>
                      </div>

                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <button type="submit" class="btn btn-danger">Deshabilitar Usuario</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
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


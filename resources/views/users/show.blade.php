@extends('layouts.dashboard')

    @section('content')
    <!-- Contenido -->
    <br>


    <section class="content">

    @include('users/errorsMsg')
      <div class="container-fluid">
        <div class="row">

          <div class="col-md-3">
            <!-- Perfil-->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  <img class="profile-user-img img-fluid img-circle"
                       src="{{ $user->profile_photo_url }}"
                       alt="User profile picture">
                </div>

                <h3 class="profile-username text-center">{{ $user->name }}</h3>
                <b>Legajo N°</b> <a class="float-right">{{ $user->legajo }}</a>
                <p class="text-muted text-center"></p>

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Alias</b> <a class="float-right">{{ $user->nickName }}</a>
                  </li>
                  <li class="list-group-item">
                    <b>email</b> <a class="float-right">{{ $user->email }}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Registrado</b> <a class="float-right">{{ $user->created_at->format('d-m-Y') }}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Ultima modificación</b> <a class="float-right">{{ $user->updated_at->format('d-m-Y') }}</a>
                  </li>
                  <li class="list-group-item">
                    <b>2FA</b> <a class="float-right">{{ $user->two_factor_secret }}</a>
                  </li>
                </ul>
              </div>
            </div>
            <!-- /.Perfil -->
          </div>

          <!-- Roles y Permisos -->
          <div class="col-md-6">
            <div class="card">

              <form method="POST" action="{{route('users.roles.update', $user)}}">
                {{csrf_field()}} {{method_field('PUT')}}
                <!-- Header Roles y Permisos -->
                  <div class="card-header p-2">
                    <ul class="nav nav-pills">
                      <li class="nav-item"><a class="nav-link active" href="#roles" data-toggle="tab">Roles del usuario</a></li>
                      <li class="nav-item"><a class="nav-link" href="#permisos" data-toggle="tab">Permisos explícitos</a></li>
                    </ul>
                  </div>
                  <!-- /.Header Roles y Permisos -->

                <div class="card-body">
                  <div class="tab-content">
                    <!-- Roles-->
                    
                    <div class="active tab-pane" id="roles">
                      <div id="accordion">
                        <div class="card">
                          <div class="card-header" id="headingRoles">
                            <h5 class="mb-0">
                              <a class="btn btn-link" data-toggle="collapse" data-target="#collapseRoles" aria-expanded="true" aria-controls="collapseRoles">
                                  Roles otorgados
                              </a>
                            </h5>
                          </div>
                          <div id="collapseRoles" class="collapse show" aria-labelledby="headingRoles" data-parent="#accordion">
                            <div class="card-body">
                              @forelse ($roles as $id => $description)
                                @if ($user->roles->contains($id))
                                  <div >
                                    <input name="roles[]"  type="checkbox" value="{{$id}}" {{ $user->roles->contains($id) ? 'checked':''}}>
                                    <label>{{$description}}</label>
                                  </div>
                                @endif                       
                              @empty
                                <small>Sin roles asignados</small>
                              @endforelse
                            </div>
                          </div>
                        </div>
    
                        <div class="card">
                          <div class="card-header" id="headingRolesAdd">
                            <h5 class="mb-0">                              
                              <a class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseRolesAdd" aria-expanded="false" aria-controls="collapseRolesAdd">
                                  Roles disponibles
                              </a>
                            </h5>
                          </div>                          
                          <div id="collapseRolesAdd" class="collapse" aria-labelledby="headingRolesAdd" data-parent="#accordion">
                            <div class="card-body">
                              @forelse ($roles as $id => $description)
                                @if (!$user->roles->contains($id))
                                <div >
                                  <input name="roles[]"  type="checkbox" value="{{$id}}" {{ $user->roles->contains($id) ? 'checked':''}}>
                                  <label>{{$description}}</label>
                                </div>
                                @endif                       
                              @empty
                                <small>Sin roles asignados</small>
                              @endforelse
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- / .Roles form-->

                    <!--Permisos form -->
                    <div class="tab-pane" id="permisos">
                      <div id="accordion">
                          <div class="card">
                            <div class="card-header" id="headingPermisos">
                              <h5 class="mb-0">
                                <a class="btn btn-link" data-toggle="collapse" data-target="#collapsePermisos" aria-expanded="true" aria-controls="collapsePermisos">
                                    Permisos : Area - App
                                </a>
                              </h5>
                            </div>
                            <div id="collapsePermisos" class="collapse show" aria-labelledby="headingPermisos" data-parent="#accordion">
                              <div class="card-body">
                                @forelse ($permissions as $id => $name)
                                    <div>
                                      <input name="permissions[]" type="checkbox" value="{{$id}}" {{ $user->permissions->contains($id) ? 'checked':''}}>
                                      <label>Permiso : {{$name}}</label>
                                    </div>
                                @empty
                                  <small>Sin permisos asignados</small>
                                @endforelse
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    <!-- / .Permisos form-->
                  </div><button class="btn btn-sm btn-block btn-outline-success">Actualizar</button>
                </div>
              
                </div>
                
              </form>
            </div>
            
          <!-- /.Roles y Permisos -->


          
          <div class="col-md-3">
            <!-- Help del panel -->          
            <div class="card text-white bg-info md-3">
  <div class="card-header">Nota</div>
  <div class="card-body">
    <h5 class="card-title">Info card title</h5>
    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
  </div>
  <div class="card-body">
    <h5 class="card-title">Info card title</h5>
    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
  </div>
</div>

          </div>



    </section>
    @stop


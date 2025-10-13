@extends('layouts.dashboard')

    @push('styles')
        <link href="/vendor/summernote/summernote-lite.min.css" rel="stylesheet">
        <link href="/vendor/select2/css/select2.min.css" rel="stylesheet" />
        {{-- <link href="/vendor/daterangepicker/daterangepicker.css" rel="stylesheet"> --}}
        <link href="/vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet">
    @endpush

@section('content')

    <br>

    @include('docs/errorsMsg')

    <div class="card card-info bg-light">
        <div class="card-header">
            <h3 class="card-title">Edición de Documento</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{route('docs.update', $doc)}}">
                {{ csrf_field() }}
                {{ method_field ('PUT')}}
                <div class="row">
                    <div class="col-sm-9">
                        <div class="form-group" {{ $errors->has('title')? 'has-error' : ''}}>
                            <label>Título</label>
                            <input  name="title" 
                                    type="text"
                                    value="{{old('title', $doc->title)}}" readonly
                                    class="form-control" 
                                    placeholder="Minimo 10, máx 80 caracteres, Unico" 
                                    minlength="10" 
                                    maxlength="80" >
                        </div>
                    </div>
                
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Área</label>
                            <select name="area" class="form-control" >
                            <option value="" selected disabled hidden >Seleccione Área</option>
                                @foreach ($areas as $area)
                                    <option value="{{ $area->id }}"
                                            {{old('area', $doc->area_id) == $area->id ? 'selected' : ''}}>
                                            {{$area->name}}
						            </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-9">

                    <div class="form-group">
                        <label>Resumen del Documento</label>
                        <textarea   name="exerpt" 
                                    type="text"
                                    class="form-control" 
                                    rows="2" 
                                    placeholder="Minimo 30, máx 280 caracteres" 
                                    minlength="30" 
                                    maxlength="280">{{old('exerpt', $doc->exerpt)}} </textarea>
                    </div>

                    <div class="form-group"></div>
                        <textarea   name="content" 
                                    class="form-control" 
                                    id="summernote" >{{old('content', $doc->content)}}</textarea>
                        <script>
                        $('#summernote').summernote({
                            placeholder: 'Contenido del documento',
                            tabsize: 2,
                            height: 300,
                            maximumImageFileSize: 512*1024,
                            toolbar: [
                                        ['style', ['bold', 'italic', 'underline', 'clear']],
                                        ['font', ['strikethrough', 'superscript', 'subscript']],
                                        ['fontsize', ['fontsize']],
                                        ['color', ['color']],
                                        ['para', ['ul', 'ol', 'paragraph']],
                                        ['height', ['height']],
                                        ['table', ['table']],
                                        
                                    ],
                            lang: 'es-ES',
                        });
                        </script>
                    </div>

                <div class="col-sm-3">

                     <div class="form-group">
                        <label>Tipo</label>
                        <select name="tags[]"
                                class="etiqueta" 
                                multiple="multiple" 
                                style="width: 100%;"
                                required>
                            @foreach ($tags as $tag)
                                <option {{ collect(old('tags', $doc->tags->pluck('id')))->contains($tag->id)? 'selected': ''}} value="{{$tag->id}}">{{old('tags[]', $tag->name)}}</option>
                            @endforeach</select>
                    </div>
                        <script>
                            $('.etiqueta').select2()
                        </script>

                    <br>

                    <div class="form-group">
                        <label> {{optional($doc->published_at)->format('d/m/Y')}}</label>
                        <p>
                            <button class="btn btn-block btn-outline-warning btn-sm" type="button" data-toggle="collapse" data-target="#collapseDate" aria-expanded="false" aria-controls="collapseExample">
                                Cambiar fecha de Publicación
                            </button>
                        </p>

                        <div class="collapse" id="collapseDate">
                            <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                <input  name="published_at" type="date"class="form-control"/>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Tipo Documento</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="public" value ="0" checked="true">
                            <label class="form-check-label">Privado</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="public" value = "1">
                            <label class="form-check-label">Público</label>
                        </div>
                    </div>
                    <hr>
                    <br>

                    <div class="form-group">
                        <div>
                            <button type="submit" class="btn btn-block btn-outline-success btn-sm">Guardar</button>
                        </div>                        
                    </div>

                </div>
            </form>
            <div class="card-body">
                <div class="float-right">
                    <form method="POST" action="{{ route('docs.destroy', $doc)}}" style="display: inline">
                    {{csrf_field()}} {{method_field('DELETE')}}
                    <button class="btn btn-block btn-outline-danger btn-sm"> Eliminar Post </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

@stop

@push('scripts')

    <script src="/vendor/jquery/jquery.min.js"></script>
    <script src="/vendor/summernote/summernote-lite.min.js"></script>
    <script src="/vendor/summernote/lang/summernote-es-ES.js"></script>
    <script src="/vendor/select2/js/select2.min.js"></script>
    <script src="/vendor/inputmask/jquery.inputmask.min.js"></script>
    <script src="/vendor/moment/moment-with-locales.js"></script>
    <script src="/vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

@endpush
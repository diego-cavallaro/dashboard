@extends('layouts.dashboard')

@push('styles')
    <link href="/vendor/summernote/summernote-lite.min.css" rel="stylesheet">
@endpush

@section('content')
   <div class="form-group">
      <textarea name="content" 
                class="form-control" 
                id="summernote" >{{$modeloHtml}}
      </textarea>
      <script>
         $('#summernote').summernote({
            placeholder: 'Contenido del documento',
            tabsize: 1,
            height: 500,
            lang: 'es-ES',
            airMode: true
         });
      </script>
   </div>

@stop

@push('scripts')
    <script src="/vendor/jquery/jquery.min.js"></script>
    <script src="/vendor/summernote/summernote-lite.min.js"></script>
    <script src="/vendor/summernote/lang/summernote-es-ES.js"></script>
@endpush
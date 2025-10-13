@extends('layouts.dashboard')

@section('content')

<section class="content">
    <br>
    <body>
        
        @foreach ($docs as $doc)
        <div class="container">    
            <div class="card">
                <div class="card-header">
                    <div class="float-right">{{ Carbon\Carbon::parse($doc['published_at'])->diffForHumans() }}</div>
                    <div class="float-left"><a href="{{route('docs.areaShow', $doc->area->url)}}">{{$doc->area->name}}</a> </div>
                    
                </div>
                <div class="card-body">
                    <h5 class="card-text">{{ $doc->title }}</h5>
                    <p class="card-text font-weight-light">{{ $doc->exerpt }}</p>
                    <a href="{{route('docs.show', $doc->url)}}" class="btn btn-success float-right btn-sm">Leer más</a>
                </div>
                <div class="card-footer text-muted">
                    @foreach ($doc->tags as $tag)
                        <div><a class="float-right" href="{{route('docs.tagShow', $tag)}}">|#{{$tag->name}}  </a></div>

                    @endforeach
                </div>
            </div>
        </div>
        @endforeach

        <div class="divider"></div>
        
        <br>
            <div class="container">  
                <div class="pagination d-flex justify-content-center">
                    {{$docs->links()}}
                </div>
            </div>
        <br>

@stop


@extends('layouts.dashboard')

@section('content')

    <br>
        <div class="container">    
            <div class="card text-dark">
                <div class="card-header">
                    <div class="text-muted float-right">
                        Tag/s :
                        @foreach ($doc->tags as $tag)
                            <cite title="Source Title">#{{ $tag->name }}</cite>
                        @endforeach
                    </div>                    
                    <div class="float-left"> Area : {{$doc->area->name}}</div>
                </div>
                
                <div class="card-body">
                    <div><p class="card-text "></p></div>
                    <p class="mb-0"><h3 class="text-center">{{$doc->title}}</h3></p>
                    <blockquote class="blockquote text-justify">
                        <div class="col px-md-5"><p class="card-text">{!!$doc->content!!}</p></div>
                        <br>

                        <footer class="blockquote-footer text-center">Autor : 
                            <cite title="Source Title">{{$doc->user->name}}</cite>
                        </footer>
                    </blockquote>
                </div>
                <br>
                    
                <div class="card-footer text-muted float-right">
                    <div class="float-right">Publicado : {{ Carbon\Carbon::parse($doc->published)->format('d-m-Y') }}</div>
                </div>
            </div>
        </div>
@stop


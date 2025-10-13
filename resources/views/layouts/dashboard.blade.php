@extends('adminlte::page')

@stack('styles')

    @section('title', 'Dashboard')

        @section('content')
            @yield('content')
        @stop

@stack('scripts')

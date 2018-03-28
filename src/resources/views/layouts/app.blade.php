@extends('layouts.base')

@section('body')
    <div id="app">
        @include('components.nav')

        <div class="container">
            @yield('content')
        </div>
    </div>
@endsection
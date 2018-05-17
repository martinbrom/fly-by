@extends('layouts/app')

@section('body')
    @include('components.nav')

    <div class="container">
        @yield('content')
    </div>
@endsection
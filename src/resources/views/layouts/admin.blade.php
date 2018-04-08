@extends('layouts/app')

@section('body')
    @include('components/admin-nav')

    <div class="admin-body">
        @yield('content')
    </div>
@endsection
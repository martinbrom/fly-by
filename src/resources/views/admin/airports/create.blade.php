@extends('layouts.admin')

@section('content')

    <h1 class="text-center">Create a new airport</h1>
    <div class="col-md-8 offset-md-2">
        <form method="post" action="{{ route('admin.airports.store') }}">
            {{ csrf_field() }}

            @include('components.form.input', ['name' => 'name', 'label' => 'Name'])
            @include('components.form.input', ['name' => 'lat',  'label' => 'Latitude'])
            @include('components.form.input', ['name' => 'lon',  'label' => 'Longitude'])
            @include('components.form.input', ['name' => 'code', 'label' => 'Code'])

            <input type="submit" value="Submit" class="btn btn-primary">
        </form>
    </div>

@endsection
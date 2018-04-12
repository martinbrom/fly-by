@extends('layouts.admin')

@section('content')

    <h1 class="text-center">Edit a specific airport</h1>
    <div class="col-md-8 offset-md-2">
        <form method="post" action="{{ route('admin.airports.update', $airport->id) }}">
            {{ csrf_field() }}
            {{ method_field('put') }}

            @include('components.form.input', ['value' => $airport->name, 'name' => 'name', 'label' => 'Name'])
            @include('components.form.input', ['value' => $airport->lat,  'name' => 'lat',  'label' => 'Latitude'])
            @include('components.form.input', ['value' => $airport->lon,  'name' => 'lon',  'label' => 'Longitude'])
            @include('components.form.input', ['value' => $airport->code, 'name' => 'code', 'label' => 'Code'])

            <input type="submit" value="Submit" class="btn btn-primary">
        </form>
    </div>

@endsection
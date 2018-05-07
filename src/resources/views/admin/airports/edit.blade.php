@extends('layouts.admin')

@section('content')

    <h1 class="text-center">Upravit letiště</h1>
    <div class="col-md-8 offset-md-2">
        <form method="post" action="{{ route('admin.airports.update', $airport->id) }}">
            {{ csrf_field() }}
            {{ method_field('put') }}

            @include('components.form.input', ['value' => $airport->name, 'name' => 'name', 'label' => 'Název'])
            @include('components.form.input', ['value' => $airport->lat,  'name' => 'lat',  'label' => 'Zeměpisná šířka'])
            @include('components.form.input', ['value' => $airport->lon,  'name' => 'lon',  'label' => 'Zeměpisná délka'])
            @include('components.form.input', ['value' => $airport->code, 'name' => 'code', 'label' => 'Kód'])

            <input type="submit" value="Upravit" class="btn btn-primary">
        </form>
    </div>

@endsection
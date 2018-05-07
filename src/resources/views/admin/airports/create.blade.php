@extends('layouts.admin')

@section('content')

    <h1 class="text-center">Nové letiště</h1>
    <div class="col-md-8 offset-md-2">
        <form method="post" action="{{ route('admin.airports.store') }}">
            {{ csrf_field() }}

            @include('components.form.input', ['name' => 'name', 'label' => 'Název'])
            @include('components.form.input', ['name' => 'lat',  'label' => 'Zeměpisná šířka'])
            @include('components.form.input', ['name' => 'lon',  'label' => 'Zeměpisná délka'])
            @include('components.form.input', ['name' => 'code', 'label' => 'Kód'])

            <input type="submit" value="Vytvořit" class="btn btn-primary">
        </form>
    </div>

@endsection
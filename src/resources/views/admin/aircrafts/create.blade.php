@extends('layouts.admin')

@section('content')

    <h1 class="text-center">Nové letadlo</h1>
    <div class="col-md-8 offset-md-2">
        <form method="post" action="{{ route('admin.aircrafts.store') }}">
            {{ csrf_field() }}

            @include('components.form.input', ['name' => 'name', 'label' => 'Název'])
            @include('components.form.input', ['type' => 'number', 'name' => 'range', 'label' => 'Dolet'])
            @include('components.form.input', ['type' => 'number', 'name' => 'speed', 'label' => 'Rychlost'])
            @include('components.form.input', ['type' => 'number', 'name' => 'cost', 'label' => 'Cena provozu'])

            <input type="submit" value="Vytvořit" class="btn btn-primary">
        </form>
    </div>

@endsection
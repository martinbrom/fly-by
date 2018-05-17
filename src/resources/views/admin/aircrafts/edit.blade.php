@extends('layouts.admin')

@section('content')

    <h1 class="text-center">Upravit letadlo</h1>
    <div class="col-md-8 offset-md-2">
        <form method="post" action="{{ route('admin.aircrafts.update', $aircraft->id) }}">
            {{ csrf_field() }}
            {{ method_field('put') }}

            @include('components.form.input', ['value' => $aircraft->name,  'name' => 'name', 'label' => 'Název'])
            @include('components.form.input', ['value' => $aircraft->range, 'type' => 'number', 'name' => 'range', 'label' => 'Dolet'])
            @include('components.form.input', ['value' => $aircraft->speed, 'type' => 'number', 'name' => 'speed', 'label' => 'Rychlost'])
            @include('components.form.input', ['value' => $aircraft->cost,  'type' => 'number', 'name' => 'cost', 'label' => 'Cena provozu'])

            <input type="submit" value="Upravit" class="btn btn-primary">
        </form>
    </div>

@endsection
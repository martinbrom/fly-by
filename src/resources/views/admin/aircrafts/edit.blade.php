@extends('layouts.admin')

@section('content')

    <h1 class="text-center">Edit a specific aircraft</h1>
    <div class="col-md-8 offset-md-2">
        <form method="post" action="{{ route('admin.aircrafts.update', $aircraft->id) }}">
            {{ csrf_field() }}
            {{ method_field('put') }}

            @include('components.form.input', ['value' => $aircraft->name,  'name' => 'name', 'label' => 'Name'])
            @include('components.form.input', ['value' => $aircraft->range, 'type' => 'number', 'name' => 'range', 'label' => 'Range'])
            @include('components.form.input', ['value' => $aircraft->speed, 'type' => 'number', 'name' => 'speed', 'label' => 'Speed'])
            @include('components.form.input', ['value' => $aircraft->cost,  'type' => 'number', 'name' => 'cost', 'label' => 'Cost'])

            <input type="submit" value="Submit" class="btn btn-primary">
        </form>
    </div>

@endsection
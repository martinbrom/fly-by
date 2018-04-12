@extends('layouts.admin')

@section('content')

    <h1 class="text-center">Create a new aircraft</h1>
    <div class="col-md-8 offset-md-2">
        <form method="post" action="{{ route('admin.aircrafts.store') }}">
            {{ csrf_field() }}

            @include('components.form.input', ['name' => 'name', 'label' => 'Name'])
            @include('components.form.input', ['type' => 'number', 'name' => 'range', 'label' => 'Range'])
            @include('components.form.input', ['type' => 'number', 'name' => 'speed', 'label' => 'Speed'])
            @include('components.form.input', ['type' => 'number', 'name' => 'cost', 'label' => 'Cost'])

            <input type="submit" value="Submit" class="btn btn-primary">
        </form>
    </div>

@endsection
@extends('layouts.admin')

@section('content')

    <h1 class="text-center">Trasa #{{ $route->id }}</h1>
    <div class="col-md-8 offset-md-2">
        <form method="post" action="{{ route('admin.routes.update', $route->id) }}">
            {{ csrf_field() }}
            {{ method_field('put') }}

            @include('components.form.input', ['name' => 'route', 'label' => 'Trasa', 'value' => $route->route])
            @include('components.form.input', ['type' => 'number', 'name' => 'airport_from_id', 'label' => 'Startovní letiště', 'value' => $route->airport_from_id])
            @include('components.form.input', ['type' => 'number', 'name' => 'airport_to_id', 'label' => 'Přistávací letiště', 'value' => $route->airport_to_id])

            <div class="form-group">
                <label for="airport_from_id" class="control-label">Startovní letiště</label>
                <select class="form-control" name="airport_from_id" id="airport_from_id">
                    @foreach($airports as $airport)
                        <option value="{{ $airport->id }}">{{ $airport->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="airport_to_id" class="control-label">Přistávací letiště</label>
                <select class="form-control" name="airport_to_id" id="airport_to_id">
                    @foreach($airports as $airport)
                        <option value="{{ $airport->id }}">{{ $airport->name }}</option>
                    @endforeach
                </select>
            </div>

            <input type="submit" value="Upravit" class="btn btn-primary">
        </form>
    </div>

@endsection
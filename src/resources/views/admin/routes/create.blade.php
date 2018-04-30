@extends('layouts.admin')

@section('content')

    <h1 class="text-center">Create new predefined route</h1>
    <div class="col-md-8 offset-md-2">
        <form method="post" action="{{ route('admin.routes.store') }}">
            {{ csrf_field() }}

            {{ $errors }}

            @include('components.form.input', ['name' => 'route', 'label' => 'Route', 'value' => old('route')])
            @include('components.form.input', ['type' => 'hidden', 'name' => 'is_predefined', 'value' => '1'])

            <div class="form-group">
                <label for="airport_from_id" class="control-label">Starting airport</label>
                <select class="form-control" name="airport_from_id" id="airport_from_id">
                    @foreach($airports as $airport)
                        <option value="{{ $airport->id }}">{{ $airport->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="airport_to_id" class="control-label">Landing airport</label>
                <select class="form-control" name="airport_to_id" id="airport_to_id">
                    @foreach($airports as $airport)
                        <option value="{{ $airport->id }}">{{ $airport->name }}</option>
                    @endforeach
                </select>
            </div>

            <input type="submit" value="Submit" class="btn btn-primary">
        </form>
    </div>

@endsection
@extends('layouts/public')

@section('content')

    {{ $errors }}

    <h1>Create new order</h1>
    <form method="post" action="{{ route('orders.store') }}">
        {{ csrf_field() }}
        @include('components.form.input', ['type' => 'email', 'name' => 'email', 'label' => 'Email address'])
        @include('components.form.input', ['name' => 'route', 'label' => 'Route', 'value' => old('route')])
        @include('components.form.input', ['type' => 'number', 'name' => 'airport_from_id', 'label' => 'Starting airport'])
        @include('components.form.input', ['type' => 'number', 'name' => 'airport_to_id', 'label' => 'Ending airport'])

        <div class="form-group">
            <label for="aircraft_airport_id" class="control-label">Aircraft</label>
            <select name="aircraft_airport_id" id="aircraft_airport_id" class="form-control">
                @foreach($aircraft_airports as $aircraft_airport)
                    <option value="{{ $aircraft_airport->id }}">{{ $aircraft_airport->aircraft->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="control-label" for="user_note">Note</label>
            <textarea class="form-control" rows="6" name="user_note" id="user_note"></textarea>
        </div>

        <input type="submit" value="Submit" class="btn btn-primary">
    </form>

@endsection

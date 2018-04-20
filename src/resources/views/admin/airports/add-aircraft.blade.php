@extends('layouts.admin')

@section('content')

    <h1>Add aircraft airport {{ $airport->name }}</h1>
    <form method="post" action="{{ route('admin.aircraft-airports.store') }}">
        {{ csrf_field() }}
        <input type="hidden" name="airport_id" value="{{ $airport->id }}">
        <!-- TODO: Maybe select multiple -->
        <label for="aircraft_id" class="control-label">Aircraft</label>
        <select name="aircraft_id" id="aircraft_id" class="form-control">
            @foreach($aircrafts as $aircraft)
                <option value="{{ $aircraft->id }}">{{ $aircraft->name }}</option>
            @endforeach
        </select>
        <input type="submit" value="Submit">
    </form>

@endsection
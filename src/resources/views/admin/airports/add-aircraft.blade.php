@extends('layouts.admin')

@section('content')

    <h1>Přidat letadlo na letiště {{ $airport->name }}</h1>
    <form method="post" action="{{ route('admin.aircraft-airports.store') }}">
        {{ csrf_field() }}
        <input type="hidden" name="airport_id" value="{{ $airport->id }}">
        <div class="form-group">
            <label for="aircraft_id" class="control-label">Letadlo</label>
            <select name="aircraft_id" id="aircraft_id" class="form-control">
                @foreach($aircrafts as $aircraft)
                    <option value="{{ $aircraft->id }}">{{ $aircraft->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <input class="btn btn-primary" type="submit" value="Přidat">
        </div>
    </form>

@endsection
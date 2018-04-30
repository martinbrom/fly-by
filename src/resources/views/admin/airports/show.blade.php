@extends('layouts.admin')

@section('content')

    <h1>Display a specific airport</h1>
    <h2>Information about a specific airport</h2>
    <p class="airport-name">{{ $airport->name }}</p>
    <a href="{{ route('admin.airports.edit', $airport->id) }}">Edit</a><br>
    <a href="{{ route('admin.airports.add-aircraft', $airport->id) }}">Add aircraft to airport</a>

    <h2>Aircrafts</h2>
    @foreach($aircraft_airports as $aircraft_airport)
        <div class="aircraft-airport">
            <h3 class="aircraft-name">{{ $aircraft_airport->aircraft->name }}</h3>

            <a href="#aircraft-airport-delete-form-{{ $aircraft_airport->id }}" onclick="event.preventDefault(); document.getElementById('aircraft-airport-delete-form-{{ $aircraft_airport->id }}').submit();">
                Remove from airport
            </a>
            <form id="aircraft-airport-delete-form-{{ $aircraft_airport->id }}" action="{{ route('admin.aircraft-airports.destroy', $aircraft_airport->id) }}" method="POST" style="display: none;">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
            </form>

            <!-- TODO: Add more aircraft data -->
            <form method="post" action="{{ route('admin.aircraft-airports.update', $aircraft_airport->id) }}">
                {{ csrf_field() }}
                {{ method_field('put') }}
                <input type="hidden" value="{{ $aircraft_airport->aircraft->id }}" name="aircraft_id">
                <label for="airport-id-select-{{ $aircraft_airport->id }}" class="control-label">Move to</label>
                <select required name="airport_id" id="airport-id-select-{{ $aircraft_airport->id }}">
                    @foreach($airports as $airport)
                        <option value="{{ $airport->id }}">{{ $airport->name }}</option>
                    @endforeach
                </select>
                <input type="submit" class="btn btn-primary" value="Submit">
            </form>
        </div>
    @endforeach

@endsection
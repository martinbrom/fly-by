@extends('layouts.admin')

@section('content')

    <h1>{{ $airport->name }}</h1>
    <p class="airport-code"><b>Kód:</b> {{ $airport->code }}</p>
    <p class="airport-lat"><b>Zeměpisná šířka:</b> {{ $airport->lat }}</p>
    <p class="airport-lon"><b>Zeměpisná délka:</b> {{ $airport->lon }}</p>
    <a href="{{ route('admin.airports.edit', $airport->id) }}">Upravit</a><br>
    <a href="{{ route('admin.airports.add-aircraft', $airport->id) }}">Přidat letadlo</a>

    <hr>
    <h2>Letadla na letišti</h2>
    @foreach($aircraft_airports as $aircraft_airport)
        <div class="aircraft-airport">
            <h3 class="aircraft-name">{{ $aircraft_airport->aircraft->name }}</h3>

            <a href="#aircraft-airport-delete-form-{{ $aircraft_airport->id }}" onclick="event.preventDefault(); document.getElementById('aircraft-airport-delete-form-{{ $aircraft_airport->id }}').submit();">
                Odebrat z letiště
            </a>
            <form id="aircraft-airport-delete-form-{{ $aircraft_airport->id }}" action="{{ route('admin.aircraft-airports.destroy', $aircraft_airport->id) }}" method="POST" style="display: none;">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
            </form>

            <form method="post" action="{{ route('admin.aircraft-airports.update', $aircraft_airport->id) }}">
                {{ csrf_field() }}
                {{ method_field('put') }}
                <input type="hidden" value="{{ $aircraft_airport->aircraft->id }}" name="aircraft_id">
                <label for="airport-id-select-{{ $aircraft_airport->id }}" class="control-label">Přesunout na: </label>
                <select required name="airport_id" id="airport-id-select-{{ $aircraft_airport->id }}">
                    @foreach($airports as $airport)
                        <option value="{{ $airport->id }}">{{ $airport->name }}</option>
                    @endforeach
                </select>
                <input type="submit" class="btn btn-primary" value="Přesunout">
            </form>
        </div>
    @endforeach

@endsection
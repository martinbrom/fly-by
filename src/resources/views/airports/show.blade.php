@extends('layouts.app')

@section('content')

    <h1>Display a specific airport</h1>
    <h2>Information about a specific airport</h2>
    <p class="airport-name">{{ $airport->name }}</p>
    <a href="{{ route('airports.edit', $airport->id) }}">Edit</a>

    <h2>Aircrafts</h2>
    @foreach($aircrafts as $aircraft)
        <div class="aircraft">
            <p class="aircraft-name">{{ $aircraft->name }}</p>
            <button class="aircraft-move">Move</button>

            <!-- TODO: Maybe confirm dialog -->
            <button class="aircraft-remove">Remove</button>
        </div>
    @endforeach

    <!-- TODO: Add aircraft to this airport -->

@endsection
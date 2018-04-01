@extends('layouts.app')

@section('content')

    <h1>List of airports</h1>

    <a href="{{ route('airports.create') }}">Add a new airport</a>

    @foreach($airports as $airport)
        <a href="{{ route('airports.show', $airport->id) }}">{{ $airport->name }}</a>
    @endforeach

@endsection
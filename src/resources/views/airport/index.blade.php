@extends('layouts.app')

@section('content')

    <h1>List of airports</h1>

    @foreach($airports as $airport)
        <a href="{{ route('airport.show', $airport->id) }}">{{ $airport->name }}</a>
    @endforeach

@endsection
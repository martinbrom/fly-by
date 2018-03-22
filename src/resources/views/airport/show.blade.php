@extends('layouts.app')

@section('content')

    <h1>Display a specific airport</h1>
    <p>{{ $airport->name }}</p>
    <a href="{{ route('airport.edit', $airport->id) }}">Edit</a>

@endsection
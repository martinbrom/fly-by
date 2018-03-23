@extends('layouts.app')

@section('content')

    <h1>Display a specific airport</h1>
    <p>{{ $airport->name }}</p>
    <a href="{{ route('airports.edit', $airport->id) }}">Edit</a>

@endsection
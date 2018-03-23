@extends('layouts.app')

@section('content')

    <h1>Display a specific aircraft</h1>
    <p>{{ $aircraft->name }}</p>
    <a href="{{ route('aircrafts.edit', $aircraft->id) }}">Edit</a>

@endsection
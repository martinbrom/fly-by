@extends('layouts.app')

@section('content')

    <h1>List of aircrafts</h1>

    @foreach($aircrafts as $aircraft)
        <a href="{{ route('aircrafts.show', $aircraft->id) }}">{{ $aircraft->name }}</a>
    @endforeach

@endsection
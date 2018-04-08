@extends('layouts.admin')

@section('content')

    <h1>Display a specific aircraft</h1>
    <h2 class="aircraft-name">{{ $aircraft->name }}</h2>
    <a href="{{ route('admin.aircrafts.edit', $aircraft->id) }}">Edit aircraft</a><br>
    <p class="aircraft-range">Range: {{ $aircraft->range }} km</p>
    <p class="aircraft-speed">Speed: {{ $aircraft->speed }} km/h</p>
    <p class="aircraft-cost">Cost: {{ $aircraft->cost }} CZK/h</p>

@endsection
@extends('layouts.admin')

@section('content')

    <h1>Display a specific aircraft</h1>

    @php($path = empty($image) ? 'images/default-aircraft-image.png' : 'storage/' . $image->path)
    <img src="{{ asset($path) }}" class="img img-responsive aircraft-image">
    <h2 class="aircraft-name">{{ $aircraft->name }}</h2>
    <div class="aircraft-links">
        <a href="{{ route('admin.aircrafts.edit', $aircraft->id) }}">Edit aircraft</a>
        <a href="{{ route('admin.aircrafts.edit-image', $aircraft->id) }}">Edit aircraft image</a>
    </div>
    <div class="aircraft-info">
        <p class="aircraft-range">Range: {{ $aircraft->range }} km</p>
        <p class="aircraft-speed">Speed: {{ $aircraft->speed }} km/h</p>
        <p class="aircraft-cost">Cost: {{ $aircraft->cost }} CZK/h</p>
    </div>

@endsection
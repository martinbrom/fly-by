@extends('layouts.admin')

@section('content')

    <h1>{{ $aircraft->name }}</h1>

    @php($path = empty($image) ? 'images/default-aircraft-image.png' : 'storage/' . $image->path)
    <img src="{{ asset($path) }}" class="img img-responsive aircraft-image">
    <h2 class="aircraft-name">{{ $aircraft->name }}</h2>
    <div class="aircraft-links">
        <a href="{{ route('admin.aircrafts.edit', $aircraft->id) }}">Upravit letadlo</a>
        <a href="{{ route('admin.aircrafts.edit-image', $aircraft->id) }}">Upravit obrázek letadla</a>
    </div>
    <div class="aircraft-info">
        <p class="aircraft-range">Dolet: {{ $aircraft->range }} km</p>
        <p class="aircraft-speed">Rychlost: {{ $aircraft->speed }} km/h</p>
        <p class="aircraft-cost">Cena provozu: {{ $aircraft->cost }} CZK/km</p>
    </div>

@endsection
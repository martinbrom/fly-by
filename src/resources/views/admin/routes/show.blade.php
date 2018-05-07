@extends('layouts/admin')

@section('content')

    @php($state_czech = $state == 'predefined' ? 'předdefinovaná' : 'běžná')
    <h1>{{ ucfirst($state_czech) }} trasa #{{ $route->id }}</h1>

    @if($state == 'predefined')
        <a href="{{ route('admin.routes.edit', $route->id) }}">Upravit trasu</a>
    @endif

    <hr>
    <div class="order-route-info row">
        <div class="col-sm-6 route-map-wrapper">

            <!-- TODO: Add map -->
            <div id="route" class="route-map" style="height: 360px; background: darkgray;"></div>
        </div>
        <div class="route-airports col-sm-6">
            <p class="route-airport-name">
                <b>Startovní letiště: </b>
                @if(!empty($route->airportFrom))
                    <a href="{{ route('admin.airports.show', $route->airportFrom->id) }}">{{ $route->airportFrom->name }}</a>
                @else
                    odstraněno
                @endif
            </p>
            <p class="route-airport-name">
                <b>Přistávací letiště: </b>
                @if(!empty($route->airportTo))
                    <a href="{{ route('admin.airports.show', $route->airportTo->id) }}">{{ $route->airportTo->name }}</a>
                @else
                    odstraněno
                @endif
            </p>
            <p class="route-distance"><b>Délka: </b> {{ $route->distance }} km</p>
        </div>
    </div>

    @if($state == 'predefined')
        <hr>
        <form class="mb-1" action="{{ route('admin.routes.destroy', $route->id) }}" method="POST" id="route-delete-form">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <input type="submit" class="btn btn-danger" value="Odebrat z předdefinovaných">
        </form>
    @endif

@endsection

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

@push('scripts')
    <script>
        let route = {!! json_encode($route) !!};
        let airports = {!! json_encode($airports) !!};

        $(document).ready(function () {
            let map = new Flb.Map('route', false);

            for (let i = 0; i < airports.length; i++) {
                map.addAirport(airports[i].id, airports[i].name, new L.LatLng(airports[i].lat, airports[i].lon));
            }

            map.chooseStartAirport(route.airport_from_id);
            map.chooseEndAirport(route.airport_to_id);

            console.log(route);
            let points = JSON.parse(route.route);

            for (let i = 0; i < points.length; i++) {
                map.route.addWayPoint(points[i]);
            }

            map.map.fitBounds(map.route.line.getBounds());
        });
    </script>

@endpush

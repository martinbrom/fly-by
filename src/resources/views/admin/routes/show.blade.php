@extends('layouts/admin')

@section('content')

    <h1>{{ ucfirst($state) }} route #{{ $route->id }}</h1>
    <a href="{{ route('admin.routes.edit', $route->id) }}">Edit route</a>

    <hr>
    <div class="order-route-info row">
        <div class="col-sm-6 route-map-wrapper">

            <!-- TODO: Add map -->
            <div id="route" class="route-map" style="height: 360px; background: darkgray;"></div>
        </div>
        <div class="route-airports col-sm-6">
            <p class="route-airport-name">
                <b>Starting airport: </b>
                @if(!empty($route->airportFrom))
                    <a href="{{ route('admin.airports.show', $route->airportFrom->id) }}">{{ $route->airportFrom->name }}</a>
                @else
                    deleted
                @endif
            </p>
            <p class="route-airport-name">
                <b>Landing airport: </b>
                @if(!empty($route->airportTo))
                    <a href="{{ route('admin.airports.show', $route->airportTo->id) }}">{{ $route->airportTo->name }}</a>
                @else
                    deleted
                @endif
            </p>
            <p class="route-distance"><b>Distance: </b> {{ $route->distance }} km</p>
            <a href="{{ route('admin.routes.show-common', $route->id) }}">Show route</a>
        </div>
    </div>

    @if($state == 'predefined')
        <hr>
        <form class="mb-1" action="{{ route('admin.routes.destroy', $route->id) }}" method="POST" id="route-delete-form">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <input type="submit" class="btn btn-danger" value="Remove route from predefined">
        </form>
    @endif

@endsection

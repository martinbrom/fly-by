@extends('layouts/admin')

@section('content')

    <h1>Objednávka #{{ $order->id }}</h1>
    <a href="{{ route('admin.orders.edit', $order->id) }}">Upravit objednávku</a>

    <hr>
    <div class="order-info">
        <p class="order-price"><b>Cena:</b> {{ $order->price }} CZK</p>
        <p class="order-flight-price"><b>Cena letu:</b> {{ $order->flight_price }} CZK</p>
        <p class="order-transport-price"><b>Cena přepravy:</b> {{ $order->transport_price }} CZK</p>
        <p class="order-duration"><b>Doba:</b> {{ $order->duration }} seconds ({{ round($order->duration / 3600, 1) }} hours)</p>
        <p class="order-code"><b>Kód:</b> {{ $order->code }}</p>
        <p class="order-email"><b>Email:</b> {{ $order->email }}</p>
        <p class="order-user-note"><b>Poznámka uživatele:</b> {{ $order->user_note ?: 'žádná poznámka' }}</p>
        <p class="order-admin-note"><b>Poznámka majitele:</b> {{ $order->admin_note ?: 'žádná poznámka' }}</p>
        <p class="order-confirmed-state"><b>Potvrzeno:</b> {{ $order->confirmed_at ?: 'zatím nepotvrzeno' }}</p>
        <p class="order-completed-state"><b>Dokončeno:</b> {{ $order->completed_at ?: 'zatím nedokončeno' }}</p>
    </div>

    <hr>
    <div class="order-route-info row">
        <div class="col-sm-6 route-map-wrapper">
            <div id="order-route-map" class="route-map" style="height: 360px; background: darkgray;"></div>
        </div>
        <div class="route-airports col-sm-6">
            <p class="route-airport-name">
                <b>Starting airport: </b>
                @if(!empty($order->route->airportFrom))
                    <a href="{{ route('admin.airports.show', $order->route->airportFrom->id) }}">{{ $order->route->airportFrom->name }}</a>
                @else
                    odstraněno
                @endif
            </p>
            <p class="route-airport-name">
                <b>Landing airport: </b>
                @if(!empty($order->route->airportTo))
                    <a href="{{ route('admin.airports.show', $order->route->airportTo->id) }}">{{ $order->route->airportTo->name }}</a>
                @else
                    odstraněno
                @endif
            </p>
            <p class="route-distance"><b>Délka: </b> {{ $order->route->distance }} km</p>
            <a href="{{ route('admin.routes.show-common', $order->route->id) }}">Zobrazit trasu</a>
        </div>
    </div>

    <hr>
    <div class="order-aircraft-info row">
        @if(!empty($order->aircraftAirport->aircraft))
            <div class="aircraft-image-wrapper col-sm-6">
                @php($path = empty($order->aircraftAirport->aircraft->image) ? 'images/default-aircraft-image.png' : 'storage/' . $order->aircraftAirport->aircraft->image->path)
                <img src="{{ asset($path) }}" class="img img-responsive w-50">
            </div>
            <div class="col-sm-6">
                <p class="aircraft-name"><b>Jméno letadla:</b> {{ $order->aircraftAirport->aircraft->name }}</p>
                <p class="aircraft-range"><b>Dolet letadla:</b> {{ $order->aircraftAirport->aircraft->range }} km</p>
                <p class="aircraft-speed"><b>Rychlost letadla:</b> {{ $order->aircraftAirport->aircraft->speed }} km/h</p>
                <p class="aircraft-cost"><b>Cena za provoz letadla:</b> {{ $order->aircraftAirport->aircraft->cost }} CZK/km</p>
                <a href="{{ route('admin.aircrafts.show', $order->aircraftAirport->aircraft->id) }}">Zobrazit letadlo</a>
            </div>
        @else
            <div class="col-sm-12">
                <b>Letadlo: </b> odstraněno
            </div>
        @endif
    </div>

    @if($order->confirmed_at == NULL)
        <hr>
        <form class="mb-1" action="{{ route('admin.orders.confirm-one', $order->id) }}" method="POST" id="order-confirm-one-form">
            {{ csrf_field() }}
            <input type="submit" class="btn btn-success" value="Potvrdit objednávku">
        </form>
    @elseif($order->completed_at == NULL)
        <hr>
        <form class="mb-1" action="{{ route('admin.orders.complete', $order->id) }}" method="POST" id="order-complete-form">
            {{ csrf_field() }}
            <input type="submit" class="btn btn-success" value="Dokončit objednávku">
        </form>
    @endif

    @if($order->completed_at == NULL)
        <form class="mb-1" action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" id="order-delete-form">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <input type="submit" class="btn btn-danger" value="Odstranit objednávku">
        </form>
    @endif

@endsection

@push('scripts')
    <script>
        let route = {!! json_encode($order->route) !!};
        let airports = {!! json_encode($airports) !!};

        $(document).ready(function () {
            let map = new Flb.Map('order-route-map', false);

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

            map.map.fitBounds(map.route.line.getBounds(), {padding : [20,20]});
        });
    </script>
@endpush

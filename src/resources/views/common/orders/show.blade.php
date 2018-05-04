@extends('layouts/app')

@section('body')

    <div class="container mb-5">
        <h1 class="mt-4">Order #{{ $order->id }}</h1>
        <!-- TODO: Link to print coupon -->

        <hr>
        <div class="order-info">
            <p class="order-price"><b>Price:</b> {{ $order->price }} CZK</p>
            <p class="order-flight-price"><b>Flight price:</b> {{ $order->flight_price }} CZK</p>
            <p class="order-transport-price"><b>Transport price:</b> {{ $order->transport_price }} CZK</p>
            <p class="order-duration"><b>Duration:</b> {{ $order->duration }} seconds ({{ round($order->duration / 3600, 1) }} hours)</p>
            <p class="order-code"><b>Code:</b> {{ $order->code }}</p>
            <p class="order-email"><b>Email:</b> {{ $order->email }}</p>
            <p class="order-user-note"><b>User note:</b> {{ $order->user_note ?: 'no note supplied' }}</p>
            <p class="order-admin-note"><b>Admin note:</b> {{ $order->admin_note ?: 'no note supplied' }}</p>
            <p class="order-confirmed-state"><b>Confirmed at:</b> {{ $order->confirmed_at ?: 'not confirmed yet' }}</p>
            <p class="order-completed-state"><b>Completed at:</b> {{ $order->completed_at ?: 'not completed yet' }}</p>
        </div>

        <hr>
        <div class="order-route-info row">
            <div class="col-sm-6 route-map-wrapper">

                <!-- TODO: Add map -->
                <div id="order-route" class="route-map" style="height: 360px; background: darkgray;"></div>
            </div>
            <div class="route-airports col-sm-6">
                <p class="route-airport-name">
                    <b>Starting airport: </b>
                    @if(!empty($order->route->airportFrom))
                        {{ $order->route->airportFrom->name }}
                    @else
                        deleted
                    @endif
                </p>
                <p class="route-airport-name">
                    <b>Landing airport: </b>
                    @if(!empty($order->route->airportTo))
                        {{ $order->route->airportTo->name }}
                    @else
                        deleted
                    @endif
                </p>
                <p class="route-distance"><b>Distance: </b> {{ $order->route->distance }} km</p>
                <a href="{{ route('admin.routes.show-common', $order->route->id) }}">Show route</a>
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
                    <p class="aircraft-name"><b>Aircraft name:</b> {{ $order->aircraftAirport->aircraft->name }}</p>
                    <p class="aircraft-range"><b>Aircraft range:</b> {{ $order->aircraftAirport->aircraft->range }} km</p>
                    <p class="aircraft-speed"><b>Aircraft speed:</b> {{ $order->aircraftAirport->aircraft->speed }} km/h</p>
                    <p class="aircraft-cost"><b>Aircraft cost:</b> {{ $order->aircraftAirport->aircraft->cost }} CZK/h</p>
                </div>
            @else
                <div class="col-sm-12">
                    <b>Aircraft: </b> deleted
                </div>
            @endif
        </div>
    </div>

@endsection

@extends('layouts/admin')

@section('content')

    <h1>Administration Landing Page</h1>
    @php
    $values = [$orderCount, $unconfirmedOrderCount, $completedOrderCount, $aircraftCount, $distinctAircraftCount, $airportCount];
    $icons  = ['exclamation', 'clock-o', 'money', 'plane', 'paper-plane', 'map-marker'];
    $texts  = ['New orders', 'Unconfirmed orders', 'Completed orders', 'Total aircrafts', 'Aircraft types', 'Total airports'];
    $colors = ['cyan', 'red', 'yellow', 'green', 'gray', 'darkgray'];
    @endphp

    <div class="overview-panel-row row">
        @for($i = 0; $i < 6; $i++)
            @include('components.admin.overview-panel', [
                'value' => $values[$i],
                'icon'  => $icons[$i],
                'text'  => $texts[$i],
                'color' => $colors[$i]
            ])
        @endfor
    </div>

@endsection

@extends('layouts/admin')

@section('content')

    <h1>Administrace</h1>
    @php
    $values = [$orderCount, $unconfirmedOrderCount, $completedOrderCount, $aircraftCount, $distinctAircraftCount, $airportCount];
    $icons  = ['exclamation', 'clock-o', 'money', 'plane', 'paper-plane', 'map-marker'];
    $texts  = ['Nové objednávky', 'Nepotvrzené objednávky', 'Dokončené objednávky', 'Všechna letadla', 'Typů letadel', 'Všechna letiště'];
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

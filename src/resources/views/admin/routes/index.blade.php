@extends('layouts/admin')

@section('content')

    <h1>{{ ucfirst($state) }} routes</h1>

    @if(count($routes) > 0)
        <table class="table table-striped table-responsive-md w-100">
            <thead class="thead-dark w-100">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Starting airport</th>
                <th scope="col">Landing airport</th>
                <th scope="col">Distance (km)</th>
                @if($state == 'predefined')
                    <th scope="col">Display</th>
                @endif
            </tr>
            </thead>
            <tbody>
                @foreach($routes as $route)
                    <tr>
                        <td class="route-id">{{ $route->id }}</td>
                        <td class="route-airport-from">{{ $route->airportFrom->name }}</td>
                        <td class="route-airport-to">{{ $route->airportTo->name }}</td>
                        <td class="route-distance">{{ $route->distance }}</td>
                        @if($state == 'predefined')
                            <td><a href="{{ route('admin.routes.show', $route->id) }}">Display</a></td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

@endsection
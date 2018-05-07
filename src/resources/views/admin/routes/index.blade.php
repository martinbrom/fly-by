@extends('layouts/admin')

@section('content')

    @php($state_czech = $state == 'predefined' ? 'předdefinované' : 'běžné')
    <h1>{{ ucfirst($state_czech) }} trasy</h1>

    @if($state == 'predefined')
        <a href="{{ route('admin.routes.create') }}">Vytvořit předdefinovanou trasu</a>
    @endif

    @if(count($routes) > 0)
        <table class="table table-striped table-responsive-md w-100">
            <thead class="thead-dark w-100">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Startovní letiště</th>
                <th scope="col">Přistávací letiště</th>
                <th scope="col">Délka (km)</th>
                <th scope="col">Zobrazit</th>
            </tr>
            </thead>
            <tbody>
                @foreach($routes as $route)
                    <tr>
                        <td class="route-id">{{ $route->id }}</td>
                        <td class="route-airport-from">{{ $route->airportFrom->name }}</td>
                        <td class="route-airport-to">{{ $route->airportTo->name }}</td>
                        <td class="route-distance">{{ $route->distance }}</td>

                        @php($display_route = $state == 'predefined' ? 'admin.routes.show' : 'admin.routes.show-common')
                        <td><a href="{{ route($display_route, $route->id) }}">Zobrazit</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

@endsection
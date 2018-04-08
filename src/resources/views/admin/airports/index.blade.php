@extends('layouts.admin')

@section('content')

    <h1>List of airports</h1>

    <a href="{{ route('admin.airports.create') }}">Add a new airport</a>

    @if(count($airports) > 0)
        <table class="table table-striped">
            <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Latitude</th>
                <th scope="col">Longitude</th>
                <th scope="col">Code</th>
                <th scope="col">Display</th>
                <th scope="col">Edit</th>
                <th scope="col">Delete</th>
            </tr>
            </thead>
            <tbody>
            @foreach($airports as $airport)
                <tr>
                    <td class="airport-id">{{ $airport->id }}</td>
                    <td class="airport-name">{{ $airport->name }}</td>
                    <td class="airport-latitude">{{ $airport->lat }}</td>
                    <td class="airport-longitude">{{ $airport->lon }}</td>
                    <td class="airport-code">{{ $airport->code }}</td>
                    <td class="airport-show"><a href="{{ route('admin.airports.show', $airport->id) }}">Display</a></td>
                    <td class="airport-edit"><a href="{{ route('admin.airports.edit', $airport->id) }}">Edit</a><br></td>

                    <td class="airport-destroy">
                        <!-- TODO: Confirm dialog -->
                        <a href="#airport-delete-form-{{ $airport->id }}" onclick="event.preventDefault(); document.getElementById('airport-delete-form-{{ $airport->id }}').submit();">
                            Delete
                        </a>
                        <form id="airport-delete-form-{{ $airport->id }}" action="{{ route('admin.airports.destroy', $airport->id) }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif

@endsection
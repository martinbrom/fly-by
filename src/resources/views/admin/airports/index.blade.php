@extends('layouts.admin')

@section('content')

    <h1>Seznam letišť</h1>

    <a href="{{ route('admin.airports.create') }}">Přidat nové letiště</a>

    @if(count($airports) > 0)
        <table class="table table-striped table-responsive-md w-100">
            <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Název</th>
                <th scope="col">Zeměpisná šířka</th>
                <th scope="col">Zeměpisná délka</th>
                <th scope="col">Kód</th>
                <th scope="col">Zobrazit</th>
                <th scope="col">Upravit</th>
                <th scope="col">Odstranit</th>
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
                    <td class="airport-show"><a href="{{ route('admin.airports.show', $airport->id) }}">Zobrazit</a></td>
                    <td class="airport-edit"><a href="{{ route('admin.airports.edit', $airport->id) }}">Upravit</a><br></td>

                    <td class="airport-destroy">
                        <a href="#airport-delete-form-{{ $airport->id }}"
                           onclick="event.preventDefault(); document.getElementById('airport-delete-form-{{ $airport->id }}').submit();">
                            Odstranit
                        </a>
                        <form id="airport-delete-form-{{ $airport->id }}"
                              action="{{ route('admin.airports.destroy', $airport->id) }}" method="POST"
                              style="display: none;">
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
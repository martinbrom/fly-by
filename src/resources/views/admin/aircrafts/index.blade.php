@extends('layouts.admin')

@section('content')

    <h1>Seznam letadel</h1>

    <a href="{{ route('admin.aircrafts.create') }}">Přidat nové letadlo</a>

    @if(count($aircrafts) > 0)
        <table class="table table-striped table-responsive-md w-100">
            <thead class="thead-dark w-100">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Název</th>
                    <th scope="col">Dolet (km)</th>
                    <th scope="col">Rychlost (km/h)</th>
                    <th scope="col">Cena (CZK/km)</th>
                    <th scope="col">Zobrazit</th>
                    <th scope="col">Upravit</th>
                    <th scope="col">Odstranit</th>
                </tr>
            </thead>
            <tbody>
                @foreach($aircrafts as $aircraft)
                    <tr>
                        <td class="aircraft-id">{{ $aircraft->id }}</td>
                        <td class="aircraft-name">{{ $aircraft->name }}</td>
                        <td class="aircraft-range">{{ $aircraft->range }}</td>
                        <td class="aircraft-speed">{{ $aircraft->speed }}</td>
                        <td class="aircraft-cost">{{ $aircraft->cost }}</td>
                        <td class="aircraft-show"><a href="{{ route('admin.aircrafts.show', $aircraft->id) }}">Zobrazit</a></td>
                        <td class="aircraft-edit"><a href="{{ route('admin.aircrafts.edit', $aircraft->id) }}">Upravit</a><br></td>

                        <td class="aircraft-destroy">
                            <a href="#aircraft-delete-form-{{ $aircraft->id }}" onclick="event.preventDefault(); document.getElementById('aircraft-delete-form-{{ $aircraft->id }}').submit();">
                                Odstranit
                            </a>
                            <form id="aircraft-delete-form-{{ $aircraft->id }}" action="{{ route('admin.aircrafts.destroy', $aircraft->id) }}" method="POST" style="display: none;">
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
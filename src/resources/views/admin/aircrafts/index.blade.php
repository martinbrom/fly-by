@extends('layouts.admin')

@section('content')

    <h1>List of aircrafts</h1>

    <a href="{{ route('admin.aircrafts.create') }}">Add a new aircraft</a>

    @if(count($aircrafts) > 0)
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Range (km)</th>
                    <th scope="col">Speed (km/h)</th>
                    <th scope="col">Cost (CZK/h)</th>
                    <th scope="col">Display</th>
                    <th scope="col">Edit</th>
                    <th scope="col">Delete</th>
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
                        <td class="aircraft-show"><a href="{{ route('admin.aircrafts.show', $aircraft->id) }}">Display</a></td>
                        <td class="aircraft-edit"><a href="{{ route('admin.aircrafts.edit', $aircraft->id) }}">Edit</a><br></td>

                        <td class="aircraft-destroy">
                            <!-- TODO: Confirm dialog -->
                            <a href="#aircraft-delete-form-{{ $aircraft->id }}" onclick="event.preventDefault(); document.getElementById('aircraft-delete-form-{{ $aircraft->id }}').submit();">
                                Delete
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
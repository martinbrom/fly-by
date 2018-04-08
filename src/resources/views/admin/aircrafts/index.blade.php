@extends('layouts.admin')

@section('content')

    <h1>List of aircrafts</h1>

    <a href="{{ route('admin.aircrafts.create') }}">Add a new aircraft</a>

    @foreach($aircrafts as $aircraft)
        <div class="aircraft">
            <h3 class="aircraft-name">{{ $aircraft->name }}</h3>
            <a href="{{ route('admin.aircrafts.show', $aircraft->id) }}">Display aircraft</a>
            <a href="{{ route('admin.aircrafts.edit', $aircraft->id) }}">Edit aircraft</a><br>
            <p class="aircraft-range">Range: {{ $aircraft->range }} km</p>
            <p class="aircraft-speed">Speed: {{ $aircraft->speed }} km/h</p>
            <p class="aircraft-cost">Cost: {{ $aircraft->cost }} CZK/h</p>

            <!-- TODO: Confirm dialog -->
            {{ Form::open(['route' => ['admin.aircrafts.destroy', $aircraft->id], 'method' => 'delete']) }}
                <button type="submit" >Delete aircraft</button>
            {{ Form::close() }}
        </div>
    @endforeach

@endsection
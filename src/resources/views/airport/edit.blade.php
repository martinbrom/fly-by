@extends('layouts.app')

@section('content')

    <h1>Edit a specific airport</h1>
    {!! Form::model($airport, ['route' => ['airport.update', $airport->id], 'method' => 'put']) !!}
        {!! Form::token() !!}

        {!! Form::label('name', 'Name') !!}
        {!! Form::text('name') !!}

        {!! Form::label('lat', 'Latitude') !!}
        {!! Form::text('lat') !!}

        {!! Form::label('lon', 'Longitude') !!}
        {!! Form::text('lon') !!}

        {!! Form::label('code', 'Code') !!}
        {!! Form::text('code') !!}

        {!! Form::submit('Submit') !!}
    {!! Form::close() !!}

@endsection
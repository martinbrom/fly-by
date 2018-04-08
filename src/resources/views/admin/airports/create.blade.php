@extends('layouts.admin')

@section('content')

    <h1>Create a new airport</h1>
    {!! Form::open(['route' => 'admin.airports.store', 'method' => 'post']) !!}
        {!! Form::token() !!}

        <div class="form-group">
            @if ($errors->has('name'))
                <div class="error">{{ $errors->first('name') }}</div>
            @endif
            {!! Form::label('name', 'Name') !!}
            {!! Form::text('name') !!}
        </div>

        <div class="form-group">
            @if ($errors->has('lat'))
                <div class="error">{{ $errors->first('lat') }}</div>
            @endif
            {!! Form::label('lat', 'Latitude') !!}
            {!! Form::text('lat') !!}
        </div>

        <div class="form-group">
            @if ($errors->has('lon'))
                <div class="error">{{ $errors->first('lon') }}</div>
            @endif
            {!! Form::label('lon', 'Longitude') !!}
            {!! Form::text('lon') !!}
        </div>

        <div class="form-group">
            @if ($errors->has('code'))
                <div class="error">{{ $errors->first('code') }}</div>
            @endif
            {!! Form::label('code', 'Code') !!}
            {!! Form::text('code') !!}
        </div>

        {!! Form::submit('Submit') !!}
    {!! Form::close() !!}

@endsection
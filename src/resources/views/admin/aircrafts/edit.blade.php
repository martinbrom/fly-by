@extends('layouts.admin')

@section('content')

    <h1>Edit a specific aircraft</h1>
    {!! Form::model($aircraft, ['route' => ['admin.aircrafts.update', $aircraft->id], 'method' => 'put']) !!}
        {!! Form::token() !!}

        {!! Form::label('name', 'Name') !!}
        {!! Form::text('name') !!}

        {!! Form::label('range', 'Range') !!}
        {!! Form::number('range') !!}

        {!! Form::label('speed', 'Speed') !!}
        {!! Form::number('speed') !!}

        {!! Form::label('cost', 'Cost') !!}
        {!! Form::number('cost') !!}

        {!! Form::submit('Submit') !!}
    {!! Form::close() !!}

@endsection
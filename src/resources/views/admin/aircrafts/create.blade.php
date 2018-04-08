@extends('layouts.admin')

@section('content')

    <h1>Create a new aircraft</h1>
    {!! Form::open(['route' => 'admin.aircrafts.store', 'method' => 'post']) !!}
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
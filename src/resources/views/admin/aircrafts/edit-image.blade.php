@extends('layouts.admin')

@section('content')

    @if(count($errors) > 0)
        {{ $errors }}
    @endif

    <h1>Edit an aircraft image</h1>
    {!! Form::open(['route' => ['admin.aircrafts.store-image', $aircraft->id], 'method' => 'post', 'files' => true]) !!}
        {!! Form::token() !!}

        {!! Form::label('image', 'Image') !!}
        {!! Form::file('image') !!}

        {!! Form::label('description', 'Description') !!}
        {!! Form::text('description') !!}

        {!! Form::submit('Submit') !!}
    {!! Form::close() !!}

    {!! Form::open(['route' => ['admin.aircrafts.default-image', $aircraft->id], 'method' => 'post']) !!}
        {!! Form::token() !!}

        {!! Form::submit('Set image to default') !!}
    {!! Form::close() !!}

@endsection
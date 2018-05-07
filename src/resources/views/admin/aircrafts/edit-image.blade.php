@extends('layouts.admin')

@section('content')

    @if(count($errors) > 0)
        {{ $errors }}
    @endif

    <h1>Upravit obrázek letadla</h1>
    {!! Form::open(['route' => ['admin.aircrafts.store-image', $aircraft->id], 'method' => 'post', 'files' => true]) !!}
        {!! Form::token() !!}

        {!! Form::label('image', 'Obrázek') !!}
        {!! Form::file('image') !!}

        {!! Form::label('description', 'Popis obrázku') !!}
        {!! Form::text('description') !!}

        {!! Form::submit('Upravit') !!}
    {!! Form::close() !!}

    {!! Form::open(['route' => ['admin.aircrafts.default-image', $aircraft->id], 'method' => 'post']) !!}
        {!! Form::token() !!}

        {!! Form::submit('Nastavit základní obrázek') !!}
    {!! Form::close() !!}

@endsection
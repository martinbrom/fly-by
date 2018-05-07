@extends('layouts.email')

@section('content')

    <p>
        Vaše objednávka <b>#{{ $order->id }}</b> byla zrušena!
    </p>

    <p>
        Pokud se domníváte, že jde o omyl, kontaktujte nás
        prosím na telefoním čísle 123456789.
    </p>

@endsection
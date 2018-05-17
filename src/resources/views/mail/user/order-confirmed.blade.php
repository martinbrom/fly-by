@extends('layouts.email')

@section('content')

    <p>
        Vaše objednávka <b>#{{ $order->id }}</b> byla potvrzena.
    </p>

    <p>
        Termín vašeho letu si můžete domluvit na telefoním čísle 123456789.
    </p>

@endsection
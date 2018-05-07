@extends('layouts.email')

@section('content')

    <p>
        Ve vaší aplikaci je nová objednávka
    </p>

    <p>
        Prohlédněte si jí
        <b><a href="{{ route('orders.show', $order->id) }}">zde</a></b>.
    </p>

@endsection
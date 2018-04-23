@extends('layouts/public')

@section('content')

    {{--{{ $order }}--}}
    <h1>Order {{ $order->id }}</h1>

    <p class="order-confirmed-state">confirmed at: {{ $order->confirmed_at ?: 'not confirmed yet' }}</p>

    <!-- TODO: Display all data -->

@endsection

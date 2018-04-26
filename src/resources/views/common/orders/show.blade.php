@extends('layouts/public')

@section('content')

    {{--{{ $order }}--}}
    <h1>Order #{{ $order->id }}</h1>

    <p class="order-confirmed-state">confirmed at: {{ $order->confirmed_at ?: 'not confirmed yet' }}</p>
    <p class="order-user-note">user note: {{ $order->user_note ?: 'no note supplied' }}</p>
    <p class="order-admin-note">admin note: {{ $order->admin_note ?: 'no note supplied' }}</p>

    <!-- TODO: Display all data -->

@endsection

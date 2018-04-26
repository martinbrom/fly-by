@extends('layouts/admin')

@section('content')

    <h1>Display a specific order</h1>

    <p class="order-code">{{ $order->code }}</p>
    <p class="order-email">{{ $order->email }}</p>
    <p class="order-confirmed-state">{{ $order->confirmed_at ?: 'not confirmed yet' }}</p>

    <!-- TODO: Display route -->
    <!-- TODO: Display aircraft-airport -->
    <!-- TODO: Display whether the aircraft has to be moved, and how much would it cost -->

    <a href="{{ route('admin.orders.edit', $order->id) }}">Edit order</a><br>
    <!-- TODO: Delete order -->
    <!-- TODO: Form with POST -->
    <a href="#order-confirm-one-form"
       onclick="event.preventDefault(); document.getElementById('order-confirm-one-form').submit();">
        Confirm order</a>
    <form id="order-confirm-one-form"
          action="{{ route('admin.orders.confirm-one', $order->id) }}" method="POST"
          style="display: none;">
        {{ csrf_field() }}
    </form>

@endsection

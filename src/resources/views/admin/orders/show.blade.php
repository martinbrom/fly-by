@extends('layouts/admin')

@section('content')

    <h1>Display a specific order</h1>

    <p class="order-code">{{ $order->code }}</p>
    <p class="order-email">{{ $order->email }}</p>
    <p class="order-confirmed-state">Confirmed at: {{ $order->confirmed_at ?: 'not confirmed yet' }}</p>
    <p class="order-completed-state">Completed at: {{ $order->completed_at ?: 'not completed yet' }}</p>

    <!-- TODO: Display route -->
    <!-- TODO: Display aircraft-airport -->
    <!-- TODO: Display whether the aircraft has to be moved, and how much would it cost -->

    <a href="{{ route('admin.orders.edit', $order->id) }}">Edit order</a><br>
    <!-- TODO: Delete order -->
    <!-- TODO: Form with POST -->

    @if($order->confirmed_at == NULL)
        <a href="#order-confirm-one-form"
           onclick="event.preventDefault(); document.getElementById('order-confirm-one-form').submit();">
            Confirm order</a>
        <form id="order-confirm-one-form"
              action="{{ route('admin.orders.confirm-one', $order->id) }}" method="POST"
              style="display: none;">
            {{ csrf_field() }}
        </form>
    @elseif($order->completed_at == NULL)
        <a href="#order-complete-form"
           onclick="event.preventDefault(); document.getElementById('order-complete-form').submit();">
            Mark order as completed</a>
        <form id="order-complete-form"
              action="{{ route('admin.orders.complete', $order->id) }}" method="POST"
              style="display: none;">
            {{ csrf_field() }}
        </form>
    @endif

@endsection

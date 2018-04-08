@extends('layouts/admin')

@section('content')

    <h1>Orders</h1>

    <button class="order-confirm-all">Confirm all orders</button>

    @foreach($orders as $order)
        <div class="order">
            <p class="order-code">{{ $order->code }}</p>
            <p class="order-email">{{ $order->email }}</p>
            <a href="{{ route('admin.orders.show', $order->id) }}">Display order information</a><br>

            <!-- TODO: Ajax confirm probably -->
            <button class="order-confirm">Confirm order</button>
        </div>
    @endforeach

@endsection
